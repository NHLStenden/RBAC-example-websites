# User Provisioning

Het proces van "User Provisioning" zorgt voor het overbrengen van identiteitsinformatie van bronsystemen naar
doelsystemen.
In ons voorbeeld gaat het om het overbrengen van medewerkersinformatie naar de LDAP-administratie.

# Initieel laden van gegevens

Na het bouwen van de verschillende containers moet er éénmalig een script gedraaid worden. Dit script zal de volgende
informatie éénmalig in de LDAP administratie zetten:

* Elke gebruiker krijgt een avatar-afbeelding (_jpegPhoto_)
* Er wordt gekeken naar de _Distinguished Name_ om te bepalen wat het _EmployeeType_ zal worden. (Zie de tabel verderop)
* Er wordt een willekeurige postcode gegeneerd (_Postalcode_)
* Er wordt een willekeurige telefoonnummer gegeneerd (_telephoneNumber_)
* Er wordt een willekeurig kamernummer gegeneerd (_Roomnumber_)
* Er wordt een willekeurig personeelsnummer gegenereerd (_EmployeeNumber_)
* De _organizationName_ krijgt altijd de waarde "NHL Stenden"

Let op: het attribuut 'organizationName' is meestal een alias voor het attribuut 'o'. 

# Aanmaken van de lijst met medewerkers

De LDAP-store heeft lange tijd gediend als primair plek voor het aanmaken van de gebruikers. Er was eerder geen
HRM-systeem
waarmee User Provisioning gedaan kon worden. Daarom is er gekozen om bij het initieel opzetten van het ecosysteem van
Docker-containers en systemen om vanuit de LDAP-administratie éénmalig de lijst met medewerkers op te bouwen, in plaats
van andersom.

| Medewerker Attribuut | LDAP-attribuut    | Regels                                          |
|----------------------|-------------------|-------------------------------------------------|
| personeelsnummer     | employeeNumber    |                                                 |
| voornaam             | givenName         |                                                 |
| achternaam           | sn                |                                                 |
| team                 | organizationName  |                                                 |
| functie              | DN                | Op basis van de DN wordt de functie vastgesteld |
| telefoonnummer       | telephoneNumber   |                                                 |
| kamernummer          | roomNumber        |                                                 |
| medewerkerType       | employeeType      |                                                 |
| postcode             | postalCode        |                                                 |

# Mapping

Bij het overzetten wordt gekeken naar informatie over de medewerker om te bepalen hoe deze in de LDAP-administratie
aangemaakt of gewijzigd moet worden.

De volgende vertaaltabel wordt gebruikt.

| Medewerker Attribuut | LDAP-attribuut    | Regels                             |
|----------------------|-------------------|------------------------------------|
| idMedewerker         | <niet gebruikt>   |                                    |
| personeelsnummer     | employeeNumber    |                                    |
| voornaam             | givenName         |                                    |
| achternaam           | sn                |                                    |
| team                 | organizationName  |                                    |
| functie              |                   | zie verderop bij rollen en locatie |
| telefoonnummer       | telephoneNumber   |                                    |
| kamernummer          | roomNumber        |                                    |
| medewerkerType       | employeeType      |                                    |
| postcode             | postalCode        |                                    |
| last_sync            | \<niet gebruikt\> |                                    |

De volgende berekende attributen worden gebruikt:

| LDAP-attribuut | Berekening                                                 | 
|----------------|------------------------------------------------------------|
| userPassword   | Altijd "Test1234!"                                         |
| cn             | {Voornaam} + " " + {Achternaam }                           |
| uid            | first({voornaam}) + {Achternaam}                           |
| objectClass    | ["inetOrgPerson", "organizationalPerson", "person", "top"] | 

De plaats waar een gebruiker aangemaakt moet worden wordt bepaald op basis van de functie van de medewerker.
Onderstaande tabel laat deze locaties zien.

| Functie              | Locatie (DN)                                    | 
|----------------------|-------------------------------------------------|
| docent               | ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com |
| medewerker ICT       | ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com    |
| medewerker marketing | ou=Marketing,ou=Staff,dc=NHLStenden,dc=com      | 
| medewerker HRM       | ou=HRM,ou=Staff,dc=NHLStenden,dc=com            |

Afhankelijk van de functie worden ook nog extra rollen toegewezen in de LDAP. Dit betekent een lidmaatschap
van de genoemde groepen via het attribuut "UniqueMember" in een object van klasse "GroupOfUniqueNames".

| Functie              | Rollen (DN)                                          | 
|----------------------|------------------------------------------------------|
| docent               | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |       
| docent               | cn=All Teachers,ou=roles,dc=NHLStenden,dc=com        |        
| docent               | cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com | 
| docent               | cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com     |      
| medewerker ICT       | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |       
| medewerker ICT       | cn=ICT Support,ou=roles,dc=NHLStenden,dc=com         |          
| medewerker marketing | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |
| medewerker marketing | cn=Marketing,ou=roles,dc=NHLStenden,dc=com           |            
| medewerker HRM       | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       |       
| medewerker HRM       | cn=HRM,ou=roles,dc=NHLStenden,dc=com                 |                 

Als een medewerker al voorkomt, dan zal de volgende informatie bijgewerkt worden:

* givenName
* sn
* telephoneNumber
* roomNumber
* postalCode
* employeeType

Daarnaast worden de standaard rollen hersteld. 

# Gebruikers die niet meer bestaan

Als een gebruiker niet langer in de HRM-database aanwezig is, dan zal het wachtwoord leeg gemaakt worden zodat de gebruiker
niet meer kan inloggen.
