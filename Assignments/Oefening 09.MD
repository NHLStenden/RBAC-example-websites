# Opdracht 9 - Aanmaken nieuwe rol

Hier gaan we onderzoeken hoe we een nieuwe rol kunnen maken om deze in te zetten voor ons personeel.

## Introductie

Stel er is een nieuw team opgezet dat marketing campagnes moet monitoren op voortgang. We noemen dit team de
"Campagne Monitors". Het aanmaken van zo'n nieuw team moet kunnen zonder dat je een programmeur nodig hebt. Dit is één
van de voornaamste redenen om in je broncode niet de rol maar permissies te controleren. Je stelt nu een Functioneel
Beheerder in staat om dit zelfstandig te doen.

## Opdracht

1. Maak via LDAP een nieuwe "GroupOfUniqueNames" aan in de DN `ou=roles,dc=NHLStenden,dc=com`.
2. Gebruik de naam "Campagne Monitors" voor de CN.
3. Login bij het Admin Panel
4. Ga naar 'Rollen' en start de synchronisatie.
    1. Als het goed is wordt de nieuwe rol uit LDAP overgenomen in de database
5. Ga terug naar de rollen
6. Zoek de regel in de tabel met 'Campagne Monitors' op en druk op de `Edit` knop om deze te wijzigen
7. Voeg de permissie 'Read a Marketing Campaign' toe

Nu gaan we een gebruiker autoriseren voor deze rol

1. Login bij het Admin Panel
2. Ga naar 'Autorisatie Aanvraag'
3. Kies de nieuwe rol 'Campagne Monitors' bij de rol
4. Kies een stafmedewerker (niet een medewerker die al bij de afdeling Marketing zit!) bijv. Sophie Bakker van HRM
5. Druk op Autoriseer

We gaan nu kijken of de nieuwe medewerker ook daadwerkelijk naar de Marketing Website mag

1. Ga naar de SharePoint [website](http://sharepoint.docker/)
2. Login als de gebruiker die je net geautoriseerd hebt
3. Zie dat er in de menu balk nu een button staat voor Marketing
4. Druk op de button Marketing
5. Login in bij de website voor Marketing (let op: als je al ingelogd was eerder moet je mogelijk daar eerst uitloggen).

Het blijkt dat we mogelijk nog niet de juiste autorisaties hebben.

1. Waarom lukt het maar niet om in te loggen op de marketing website? Welke autorisatie mist er (zie de tabel bij
   opdracht 7)
2. Ken de missende autorisaties toe aan de rol 'Campagne Monitors'
3. Probeer opnieuw in te loggen.

Nog steeds zal het niet lukken om in te loggen op de website van Marketing. Dit heeft te maken met de beveiliging op
applicatie niveau. Om gebruik te maken van de website moet je de rol 'Marketing' hebben. Het is dus niet genoeg om
allerlei Marketing-*permissies* te hebben. Je moet ook de rol "Marketing" hebben.

Echter aan de rol van Marketing zitten de nodige permissies waardoor je meteen ook bijna alles mag. Deze rol is dus
eigenlijk veel te ruim opgezet: als je de rol Marketing heb mag je meteen teveel. We kunnen minder autorisaties in die
rol opnemen en een nieuwe rol maken voor de standaard permissies van een echte Marketing medewerker. Echter, dan moeten
we wel meteen alle gebruikers toewijzen aan die nieuwe rol.

# Navigatie

Navigeer hier naar de volgende [opdracht](./Oefening%2010.MD) of de vorige [opdracht](./Oefening%2008.MD).