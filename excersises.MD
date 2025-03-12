# Oefeningen

Als de Docker containers draaien is het tijd om een aantal opdrachten en oefeningen uit te voeren. Deze worden in
dit document verder uitgewerkt. De oplossingen / uitwerkingen staan aan het einde van het document.

Als je met onderstaande opdrachten aan de slag gaat dan kun je onderstaande tabel gebruiken voor de initiële login gegevens.

| URL                       | rol       | username   |
| --------------------------|-----------|------------|
| http://marketing.docker/  | gebruiker | `fbos`     | 
| http://grades.docker/     | student   | `edeboer`  |
| http://grades.docker/     | teacher   | `gwillems` |
| http://admin.docker/      | admin     | `tvisser`  |
| http://sharepoint.docker/ | student   | `edeboer`  |
| http://sharepoint.docker/ | teacher   | `gwillems` |

Er verschijnt een popup waar je moet inloggen. Het wachtwoord is altijd `Test1234!`. 

Later zul je ook op een andere manier gaan inloggen. De instructies daarvoor volgen in de opdrachten. 

## Opdrachten

De volgende opdrachten en oefeningen zijn opgenomen:

1. In kaart brengen van beveiliging
2. Inzicht in account administratie
3. Accounts gebruiken om in te loggen
4. Handmatige aanpassing van toegangsrechten
5. Ongewenste toegangsrechten
6. Inzicht in rollen - gebruikers
7. Inzicht in rollen - permissies
8. 


# Opdracht 1

De eerste opdracht betreft het in kaart brengen van de huidige stand van zaken over toegangsbeveiliging. 

## Instructie

Er zijn enkele websites gemaakt die steeds bestemd zijn voor een ander doel. Zo is er een website voor marketing campagnes,
een website voor de cijfer administratie en een soort Intranet pagina waar informatie gedeeld kan worden met medewerkers
en studenten. 

## Opdracht

Login bij elke website en noteer welke mogelijkheden de gebruiker heeft. Gebruik onderstaande tabel voor de juiste
accounts. Vul bij 'Toegang' in welke functionele mogelijkheden je ziet (bijvoorbeeld in de menu balk).

| URL                       | rol       | username   | Toegang |
| --------------------------|-----------|------------|---------| 
| http://marketing.docker/  | gebruiker | `fbos`     |         | 
| http://grades.docker/     | student   | `edeboer`  |         |
| http://grades.docker/     | teacher   | `gwillems` |         |
| http://admin.docker/      | admin     | `tvisser`  |         |
| http://sharepoint.docker/ | student   | `edeboer`  |         |
| http://sharepoint.docker/ | teacher   | `gwillems` |         |

## Vragen
1. Is het verstandig of juist onverstandig vanuit een _security perspective_ om te werken met zoveel verschillende 
 websites? Zou je niet liever zoveel mogelijk zaken combineren? Motiveer je antwoord.

# Opdracht 2

In deze opdracht gaan we in de account administratie kijken naar meer informatie. Deze is opgenomen in een zogenaamde
*LDAP-store*.

## Instructie

Start nu een LDAP management tool zoals de _Apache Directory Studio_. Zie de [readme.MD](./README.md) voor instructies.

## Opdracht

Navigeer in de _Directory Information Tree_ (**DIT**) naar onderstaande locatie:

    dc=NHLStenden,dc=com > ou=Opleidingen > ou=Teachers

De zogenaamde **Distinguished Name** van deze locatie is

    ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com

Je ziet hier een aantal gebruikersaccounts. Zoek de account *'Guus Willems'* en klik hier op. Je krijgt de eigenschappen te zien.

![opdracht 2 - 02 user details gwillems.png](images/excersises/opdracht%202%20-%2002%20user%20details%20gwillems.png)

## Vragen

1. Welke eigenschap van het account heb je gebruikt om mee in te loggen?

## Opdracht 3

## Instructie

Zoek nu een andere _student_ of _teacher_ op in deze lijst met accounts. De studenten vind je op

    dc=NHLStenden,dc=com > ou=Opleidingen > ou=Teachers

## Opdracht

Log in bij de website [http://grades.docker/](http://grades.docker/) met diverse rollen (student, teacher).

## Vragen

## Opdracht 4

## Introductie

Kijk in de LDAP administratie bij de administratie van de opleidingen. Er zijn twee opleidingen:

* HBO-ICT
* AD CS&S

Elke opleiding is opgedeeld in twee soorten gebruikers:

* Student
* Teacher

Er zijn veel manieren om je gebruikers in te delen in deze (LDAP-) administratie. In deze situatie is gekozen om voor elke
combinatie van *opleiding* en *soort gebruiker* een **rol** aan te maken. Dat levert dus vier rollen op:

* Student bij HBO-ICT
* Student bij AD CS&S
* Teacher bij HBO-ICT
* Teacher bij AD CS&S

Deze vind je op de locatie

    cn=Teachers ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com
    
en

    cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com    

Er zijn twee docenten die bij beide opleidingen lesgeven. Dit zijn onderstaande docenten.

* Isabel Dekker
* Liam de Koning

Het is dus mogelijk om meerdere rollen toe te wijzen aan één account (persoon). Dit is vaak in de echte wereld ook gebruikelijk.

## Opdracht

1. Verwijder de rol "Teacher HBO-ICT " bij Isabel Dekker. 
   2. Ga naar cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com
   3. Open het attribuut  "Unique Members"
   4. Selecteer "cn=Isabel Dekker,ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com"
   5. verwijder deze (Rechter muisknop 'delete *value*') of de delete-knop op je toetsenbord
2. Ververs de pagina in de website. Wat is er veranderd?

We hebben nu dus in de administratie van de accounts een rol handmatig ingetrokken.

## Vragen

1. Is het verstandig om dergelijke aanpassingen handmatig te doen?
2. Wat zou de bron moeten zijn voor deze informatie?
3. In welke situatie zou een handmatige actie nodig kunnen zijn?

## Opdracht 5


## Introductie
Bij Marketing kan na inloggen met de user `fbos` onderstaande rechten gebruikt worden:

![opdracht 1 - 01.png](images/excersises/opdracht%201%20-%2001.png)

Bij inloggen op de admin zien we deze knoppen

![opdracht 1 - 02.png](images/excersises/opdracht%201%20-%2002.png)

## Instructie

## Opdracht

## Vragen



# Reflectie

## Opdracht 3

## Instructie

## Introductie

## Opdracht

## Vragen



# Reflectie
