# Opdracht 4 - Handmatige aanpassing van toegangsrechten

## Introductie

Kijk in de LDAP-administratie bij de administratie van de opleidingen. Er zijn twee opleidingen:

* HBO-ICT
* AD CS&S

Elke opleiding is opgedeeld in twee soorten gebruikers:

* Student
* Teacher

Er zijn veel manieren om je gebruikers in te delen in deze (LDAP-) administratie. In deze situatie is gekozen om voor
elke combinatie van *opleiding* en *soort gebruiker* een **rol** aan te maken. Dat levert dus vier rollen op:

* Student bij HBO-ICT
* Student bij AD CS&S
* Teacher bij HBO-ICT
* Teacher bij AD CS&S

Deze vind je op de locatie

> cn=Teachers ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com

en

> cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com

Er zijn twee docenten die bij beide opleidingen lesgeven. Dit zijn onderstaande docenten.

* Isabel Dekker
* Liam de Koning

Het is dus mogelijk om meerdere rollen toe te wijzen aan één account (persoon). Dit is vaak in de echte wereld ook
gebruikelijk.

## Opdracht

1. Verwijder de rol "Teacher HBO-ICT" bij Isabel Dekker.
    1. Ga naar "cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com"
    2. Open het attribuut  "Unique Members"
    3. Selecteer "cn=Isabel Dekker,ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com"
    4. verwijder deze (Rechter muisknop 'delete *value*') of de delete-knop op je toetsenbord
2. Ververs de pagina in de website. Wat is er veranderd?

We hebben nu dus in de administratie van de accounts een rol handmatig ingetrokken.

## Vragen

1. Is het verstandig om dergelijke aanpassingen handmatig te doen?
2. Wat zou de bron moeten zijn voor deze informatie?
3. In welke situatie zou een handmatige actie nodig kunnen zijn?

