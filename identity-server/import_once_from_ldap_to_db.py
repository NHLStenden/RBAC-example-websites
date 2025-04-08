# Python script to import the staff/teacher accounts to the HRM Database
# This is run once (manually!) after installation when all docker containers are running.
# Started from the script "slapd-load-entries.sh"
#

import mysql.connector
from ldap3 import Server, Connection, SUBTREE

# ✅ LDAP instellingen
LDAP_SERVER = "ldap://identityserver"
LDAP_USER = "cn=admin,dc=NHLStenden,dc=com"
LDAP_PASSWORD = "test12345!"
SEARCH_BASE = "dc=NHLStenden,dc=com"

# ✅ MariaDB instellingen
DB_CONFIG = {
    "host": "iam-example-hrm-server",
    "port": 3306,
    "user": "admin",
    "password": "Test1234!",
    "database": "HRM"
}

# ✅ Mapping van DN naar functie
dn_naar_functie = {
    "ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com": "docent",
    "ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com": "medewerker ICT",
    "ou=Marketing,ou=Staff,dc=NHLStenden,dc=com":"medewerker marketing",
    "ou=HRM,ou=Staff,dc=NHLStenden,dc=com":"medewerker HRM",
}
def clear_table(cursor):
    """Leegt de medewerkers-tabel."""
    print("🗑️ Tabel leegmaken...")
    cursor.execute("DELETE FROM medewerkers;")
    print("✅ Tabel is geleegd.")

def ldap_connect():
    """Maakt verbinding met LDAP en haalt alle medewerkers op."""
    server = Server(LDAP_SERVER)
    conn = Connection(server, LDAP_USER, LDAP_PASSWORD, auto_bind=True)
    conn.search(SEARCH_BASE, "(objectClass=INetOrgPerson)", attributes=["*"])
    return conn.entries

def insert_into_mariadb(medewerkers):
    """Voegt medewerkers toe aan de MariaDB-database."""
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()

    clear_table(cursor)

    for medewerker in medewerkers:
        fullname = str(medewerker.cn)
        dn = str(medewerker.entry_dn)

        # Extract functie uit DN
        functie = None
        for ou, functie_naam in dn_naar_functie.items():
            if ou in dn:
                functie = functie_naam
                break

        if not functie:
            print(f"⚠️ Geen functie gevonden voor {dn}, overslaan...")
            continue

        # ✅ SQL INSERT


        postcode = str(medewerker.postalCode)
        telefoon = str(medewerker.telephoneNumber)
        voornaam = str(medewerker.givenName)
        achternaam = str(medewerker.sn)
        medewerkerType = str(medewerker.employeeType)
        employeeNr = str(medewerker.employeeNumber)
        kamernr = str(medewerker.roomNumber)
        team = str(medewerker.organizationName)


        sql = """
        INSERT INTO medewerkers (
                personeelsnummer,
                voornaam,
                achternaam,
                team,
                functie,
                telefoonnummer,
                kamernummer,
                medewerkerType,
                postcode)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s);
        """

        cursor.execute(sql, (employeeNr,voornaam, achternaam, team, functie, telefoon, kamernr, medewerkerType, postcode))
        print(f"✅ Toegevoegd: {voornaam} {achternaam} als {functie}")

    conn.commit()
    cursor.close()
    conn.close()

if __name__ == "__main__":
    medewerkers = ldap_connect()
    insert_into_mariadb(medewerkers)
