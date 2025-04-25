import mysql.connector
from ldap3 import Server, Connection, ALL, MODIFY_REPLACE, MODIFY_ADD, MODIFY_DELETE
import hashlib
import base64

# Configuratie voor MariaDB
DB_CONFIG = {
    "host": "iam-example-hrm-server",
    "user": "admin",
    "port": 3306,
    "password": "Test1234!",
    "database": "HRM",
}

# Configuratie voor MariaDB IAM Database
DB_CONFIG_AUDITTRAIL = {
    "host": "iam-example-db-server",
    "user": "student",
    "port": 3306,
    "password": "test1234",
    "database": "IAM",
}

# Configuratie voor toegang tot LDAP
LDAP_CONFIG = {
    "server": "ldap://identityserver",
    "user": "cn=admin,dc=NHLStenden,dc=com",
    "password": "test12345!",
    "base_dn": "dc=NHLStenden,dc=com",
}

# Mapping van functie naar LDAP OU (Organizational Unit)
# Dit bepaalt waar gebruikersaccounts aangemaakt moeten worden op basis van de functie van een medewerker
#
FUNCTIE_TO_DN = {
    "docent": "ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com",
    "medewerker ICT": "ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com",
    "medewerker marketing": "ou=Marketing,ou=Staff,dc=NHLStenden,dc=com",
    "medewerker HRM": "ou=HRM,ou=Staff,dc=NHLStenden,dc=com",
}

# Lijst met default rollen per type medewerker
# Dit bepaalt bij het wijzigen en aanmaken van een account welke rollen (lidmaatschap van GroupOfUniqueNames) een
# bepaalde functie toegekend krijgt. Deze worden bij elke synchronisatie afgedwongen!
#
FUNCTIE_TO_ROLES = {
    "docent": [
        "cn=All Personell,ou=roles,dc=NHLStenden,dc=com",
        "cn=All Teachers,ou=roles,dc=NHLStenden,dc=com",
        "cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com",
        "cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com"
    ],
    "medewerker ICT": [
        "cn=All Personell,ou=roles,dc=NHLStenden,dc=com",
        "cn=ICT Support,ou=roles,dc=NHLStenden,dc=com"
    ],
    "medewerker marketing": [
        "cn=All Personell,ou=roles,dc=NHLStenden,dc=com",
        "cn=Marketing,ou=roles,dc=NHLStenden,dc=com"
    ],
    "medewerker HRM": [
        "cn=All Personell,ou=roles,dc=NHLStenden,dc=com",
        "cn=HRM,ou=roles,dc=NHLStenden,dc=com",
    ],

}


# Verbinding maken met MariaDB
def connect_db():
    return mysql.connector.connect(**DB_CONFIG)

def connect_db_audittrail():
    return mysql.connector.connect(**DB_CONFIG_AUDITTRAIL)


# Verbinding maken met LDAP
def connect_ldap():
    server = Server(LDAP_CONFIG["server"], get_info=ALL)
    conn = Connection(server, LDAP_CONFIG["user"], LDAP_CONFIG["password"], auto_bind=True)
    return conn

def log_audit(category, code, level, description):
    db = connect_db_audittrail()
    cursor = db.cursor()
    query = """
        INSERT INTO audittrail (category, code, level, username, description)
        VALUES (%s, %s, %s, %s, %s)
    """
    cursor.execute(query, (category, code, level, "UserProv", description))
    db.commit()
    db.close()


# Haal medewerkers op uit MariaDB
def get_medewerkers():
    db = connect_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("SELECT * FROM medewerkers")
    medewerkers = cursor.fetchall()
    db.close()
    return medewerkers

def get_medewerkers_employeeNumber():
    db = connect_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("SELECT personeelsnummer FROM medewerkers")
    medewerkers = {row["personeelsnummer"] for row in cursor.fetchall()}
    db.close()
    return medewerkers

def verwerk_achternaam(achternaam):
    tussenvoegsels_map = {
        'van der': 'vd',
        'van de': 'vd',
        'van den': 'vd',
        'van': 'van',
        'de': 'de',
        'den': 'den',
        'der': 'der',
        'te': 'te',
        'ten': 'ten',
        'ter': 'ter'
    }

    parts = achternaam.strip().lower().split()
    uid_parts = []

    i = 0
    while i < len(parts):
        # Probeer samengestelde tussenvoegsels eerst
        if i + 1 < len(parts):
            samengestelde = f"{parts[i]} {parts[i+1]}"
            if samengestelde in tussenvoegsels_map:
                uid_parts.append(tussenvoegsels_map[samengestelde])
                i += 2
                continue
        if parts[i] in tussenvoegsels_map:
            uid_parts.append(tussenvoegsels_map[parts[i]])
        else:
            uid_parts.append(parts[i])
        i += 1

    return ''.join(uid_parts)

def generate_uid(voornaam, achternaam, personeelsnummer):
    if '-' in achternaam:
        hoofdnaam, meisjesnaam = [deel.strip() for deel in achternaam.split('-', 1)]
        hoofd_uid = verwerk_achternaam(hoofdnaam)
        meisjes_uid = verwerk_achternaam(meisjesnaam)
        base_uid = f"{voornaam[0].lower()}{hoofd_uid}.{meisjes_uid}"
    else:
        achternaam_uid = verwerk_achternaam(achternaam)
        base_uid = f"{voornaam[0].lower()}{achternaam_uid}"

    return base_uid if len(base_uid) > 1 else f"{base_uid}{personeelsnummer}"

# Zoek een medewerker in LDAP op basis van personeelsnummer
def find_medewerker_by_personeelsnummer(conn, personeelsnummer):
    search_base = LDAP_CONFIG["base_dn"]
    search_filter = f"(employeeNumber={personeelsnummer})"

    print(f"Searching for {personeelsnummer} medewerker")

    conn.search(search_base, search_filter, attributes=["*"])

    if conn.entries:
        return conn.entries[0].entry_dn  # Geeft de bestaande DN terug
    return None


# Bepaal hoe de DN van een medewerker er uit moet zien op basis van de functie en naam-gegevens
def genereer_dn(medewerker):
    functie = medewerker["functie"]
    ou_dn = FUNCTIE_TO_DN.get(functie, "")
    fullname = f"{medewerker['voornaam']} {medewerker['achternaam']}"
    dn = f"cn={fullname},{ou_dn}"
    return dn


# Voeg een medewerker toe aan LDAP
def add_medewerker_to_ldap(conn, medewerker):
    medewerkertype = medewerker["functie"]
    voornaam = medewerker["voornaam"]
    achternaam = medewerker["achternaam"]
    print(f"➡️ Toevoegen {voornaam} {achternaam} aan LDAP met medewerkertype : {medewerkertype}")

    dn = genereer_dn(medewerker)
    print(f"DN = {dn}")

    uid = generate_uid(voornaam, achternaam, medewerker["personeelsnummer"])
    password = "Test1234!"
    password_hash = "{SHA}" + base64.b64encode(hashlib.sha1(password.encode()).digest()).decode()

    attributes = {
        "objectClass": ["inetOrgPerson", "organizationalPerson", "person", "top"],
        "uid": uid,
        "employeeNumber": str(medewerker["personeelsnummer"]),
        "givenName": medewerker["voornaam"],
        "sn": medewerker["achternaam"],
        "cn": f"{medewerker['voornaam']} {medewerker['achternaam']}",
        "telephoneNumber": medewerker["telefoonnummer"] or "",
        "roomNumber": medewerker["kamernummer"] or "",
        "postalCode": medewerker["postcode"] or "",
        "userPassword": password_hash,  # Voeg gehashte versie toe,
        "employeeType": medewerker["medewerkerType"],
        "organizationName": medewerker["team"],
    }
    print(f"➡️ Toevoegen aan LDAP met DN: {dn}")

    if conn.add(dn, attributes=attributes):
        print(f"✅ Toegevoegd aan LDAP: {dn}")
        log_audit("LDAP", "ADD", "INFO", f"Nieuwe medewerker toegevoegd: [{dn}]")
        updateLastSyncTimestampForUser(medewerker["idMedewerker"])
    else:
        print(f"❌ Fout bij toevoegen: {conn.result}")
    return dn


# Update een bestaande medewerker in LDAP
def update_medewerker_in_ldap(conn, dn, medewerker):
    medewerkerType = medewerker["medewerkerType"]

    voornaam = medewerker["voornaam"]
    achternaam = medewerker["achternaam"]

    uid = generate_uid(voornaam, achternaam, medewerker["personeelsnummer"])

    changes = {
        "givenName": [(MODIFY_REPLACE, [voornaam])],
        "sn": [(MODIFY_REPLACE, [achternaam])],
        "telephoneNumber": [(MODIFY_REPLACE, [medewerker["telefoonnummer"] or ""])],
        "roomNumber": [(MODIFY_REPLACE, [medewerker["kamernummer"] or ""])],
        "postalCode": [(MODIFY_REPLACE, [medewerker["postcode"] or ""])],
        "employeeType": [(MODIFY_REPLACE, [medewerkerType or ""])],
        "organizationName": [(MODIFY_REPLACE, [medewerker["team"] or ""])],
        "uid": [(MODIFY_REPLACE, [uid or ""])],
    }

    if conn.modify(dn, changes):
        print(f"🔄 Bijgewerkt in LDAP: {dn} met type {medewerkerType} / uid = {uid}")
        # log_audit("LDAP", "UPDATE", "INFO", f"Bijgewerkt: {dn}")
        updateLastSyncTimestampForUser(medewerker["idMedewerker"])
    else:
        print(f"❌ Fout bij bijwerken: {conn.result} ({medewerkerType})")


# ALs een gebruiker gewijzigd is in de LDAP dan zal het moment van de laatste synchronisatie opgenomen worden in
# de database bij het veld "last_sync"
def updateLastSyncTimestampForUser(idMedewerker):
    db = connect_db()
    cursor = db.cursor()
    query = "UPDATE medewerkers SET last_sync = NOW() WHERE idMedewerker = %s"
    cursor.execute(query, (idMedewerker,))
    db.commit()
    db.close()


# Zorg dat een medewerker alle default rollen krijgt.
# Hiervoor wordt de lijst 'FUNCTIE_TO_ROLES' gebruikt.
def voeg_medewerker_toe_aan_rollen(conn, functie, medewerker_dn):

    # Haal de bijbehorende rollen op
    rollen = FUNCTIE_TO_ROLES.get(functie, [])

    if not rollen:
        print(f"⚠️ Geen rollen gevonden voor functie: {functie}. Medewerker krijgt geen extra rechten.")
        return

    for rol_dn in rollen:
        # Controleer of de rol bestaat in LDAP
        if not conn.search(rol_dn, "(objectClass=groupOfUniqueNames)"):
            print(f"❌ Fout: de rol {rol_dn} bestaat niet in LDAP! Eerst aanmaken.")
            continue

        # Voeg de medewerker toe aan de groep
        conn.modify(rol_dn, {"uniqueMember": [(MODIFY_ADD, [medewerker_dn])]})
        print(f"✅ Medewerker {medewerker_dn} toegevoegd aan rol {rol_dn}")

# Als een gebruikersaccount verplaatst wordt dan kan in de LDAP een ghost-entry in de GroupOfUniqueNames lijst komen
# bij het attribuut "UniqueMember".
# Deze functie loopt de hele LDAP af en zoekt naar deze lidmaatschappen en ruimt deze op.
def verwijder_oude_groepslidmaatschappen(conn, old_dn):
    print(f"🧹 Verwijderen van oude groepslidmaatschappen voor {old_dn}")
    search_filter = f"(uniqueMember={old_dn})"

    # Geen "dn" als attribuut opvragen!
    conn.search(LDAP_CONFIG["base_dn"], search_filter, attributes=["uniqueMember"])

    for entry in conn.entries:
        group_dn = entry.entry_dn
        print(f"🗑️ Verwijderen uit groep: {group_dn}")
        success = conn.modify(group_dn, {
            "uniqueMember": [(MODIFY_DELETE, [old_dn])]
        })

        if success:
            log_audit("AUTHOR", "REMOVE", "INFO", f"Remove user [{old_dn}] from role: [{group_dn}]")
            print(f"✅ Verwijderd uit groep: {group_dn}")
        else:
            print(f"❌ Fout bij verwijderen uit {group_dn}: {conn.result}")

# Hoofdproces: synchroniseren MariaDB → LDAP
def sync_medewerkers():
    conn_ldap = connect_ldap()
    medewerkers = get_medewerkers()

    for medewerker in medewerkers:
        dn = find_medewerker_by_personeelsnummer(conn_ldap, medewerker["personeelsnummer"])
        if dn:
            correct_dn = genereer_dn(medewerker)
            if dn.lower() != correct_dn.lower():
                # De gebruiker zit in de verkeerde OU → verplaatsen
                print(f"🔁 Verplaatsen {dn} naar {correct_dn}")

                old_dn = dn

                new_rdn = correct_dn.split(',')[0]  # bijv: "cn=Jan Jansen"
                new_superior = ','.join(correct_dn.split(',')[1:])  # bijv: "ou=HRM,ou=Staff,dc=NHLStenden,dc=com"

                if conn_ldap.modify_dn(dn, new_rdn, new_superior=new_superior, delete_old_dn=True):
                    print(f"✅ Verplaatst naar juiste OU: {correct_dn}")
                    verwijder_oude_groepslidmaatschappen(conn_ldap, old_dn)

                    dn = correct_dn  # Update DN zodat roltoewijzing klopt
                else:
                    print(f"❌ Fout bij verplaatsen: {conn_ldap.result}")

            update_medewerker_in_ldap(conn_ldap, dn, medewerker)
        else:
            dn = add_medewerker_to_ldap(conn_ldap, medewerker)

        voeg_medewerker_toe_aan_rollen(conn_ldap, medewerker["functie"], dn)

    conn_ldap.unbind()


# Zoek alle gebruikers in LDAP
def get_all_ldap_users(conn):
    search_filter = "(&(objectClass=inetOrgPerson)(!(employeeType=Student)))"
    conn.search(LDAP_CONFIG["base_dn"], search_filter, attributes=["employeeNumber"])
    ldap_users = {entry.entry_dn: entry.employeeNumber.value for entry in conn.entries if entry.employeeNumber}
    return ldap_users


# Wachtwoord leegmaken voor een inactieve medewerker
def deactivate_ldap_account(conn, dn):
    if conn.modify(dn, {"userPassword": [(MODIFY_REPLACE, [""])]}):
        log_audit("LDAP", "MOVE", "INFO", f"Disable account: [{dn}]")
        print(f"⚠️ Wachtwoord geleegd voor gedeactiveerd account: {dn}")
    else:
        print(f"❌ Fout bij wachtwoord legen voor {dn}: {conn.result}")


# Controleer welke medewerkers niet meer in de database staan en deactiveer hun account
def deactivate_removed_users():
    print("------------------------------------------------------------------------------------------")
    print("---------------------- Opruimen accounts die niet meer in medewerkerslijst staan----------")
    print("------------------------------------------------------------------------------------------")
    conn_ldap = connect_ldap()
    actieve_medewerkers = get_medewerkers_employeeNumber()
    actieve_medewerkers = {str(num) for num in actieve_medewerkers}

    ldap_users = get_all_ldap_users(conn_ldap)

    for dn, employee_number in ldap_users.items():
        print(f"- testing employee {dn}")
        if str(employee_number) not in actieve_medewerkers:
            deactivate_ldap_account(conn_ldap, dn)

    conn_ldap.unbind()


# Voer de synchronisatie uit
if __name__ == "__main__":
    sync_medewerkers()
    deactivate_removed_users()
