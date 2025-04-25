# Uitwerkingen

Hier vind je de uitwerkingen van de opgaven.



## Opdracht 1

Door in te loggen op de LDAP-administratie kan gekeken worden naar de structuur van de account administratie.
Het personeel van Marketing is geen onderwijzend personeel dus valt onder Staff/Marketing.

![opdracht 1 - 01.png](images/opdracht%201%20-%2001.png)

De aanname is dat je verwacht dat deze groep gebruikers inderdaad bij de applicatie voor Marketing website hoort. Dat
kun je alleen zeker weten door het aan de programmeur of een website beheer te vragen of in de documentatie te kijken.

In dit geval kun je het afleiden uit de configuratie van de webserver. Kijk op Github op de volgende locatie:

* [marketing.conf](../webserver/conf/marketing.conf) (webserver --> conf --> marketing.conf)

En zoek naar het onderstaande configuratie item:

```apacheconf
 Require ldap-group cn=Marketing,ou=roles,dc=NHLStenden,dc=com
```

Daar staat netjes dat het intranet alleen beschikbaar is voor de genoemde groep. 

## Opdracht 2

Het veld waarmee je probeert in te loggen is het veld *UID*. Dit veld moet uniek zijn in de administratie
maar in het geval van LDAP is daar geen bewaking op. 

Als je in de rest van de opdrachten dus gevraagd wordt om in te loggen dan kun je een gebruiker opzoeken in de
LDAP administratie en kijken naar het veld *UID* en daarmee inloggen.

## Opdracht 3

Als je werkt met toegangsrechten in applicaties dan is het fijn als je niet een webpagina kunt oproepen
waar je toch niks mee mag doen. Dus kun je de navigatie aanpassen op basis van de toegekende toegangsrechten. We
zien dat hier ook duidelijk terugkomen in de navigatiebalk. 

## Opdracht 4

Het is niet verstandig om dergelijke wijzigingen rechtstreeks toe te passen in een identity provider administratie. De
reden hierachter is dat het niet te achterhalen is waarom deze wijziging gedaan is en er is geen opbouw van historische
administratie. We komen hier verder op terug in opdracht 10.

## Opdracht 5

## Opdracht 6

## Opdracht 7

Wat we nu gedaan hebben is een permissie aan een verkeerde groep personen toegekend. Dit zal in dit geval niet de 
bedoeling zijn geweest. Dit komt omdat er bijvoorbeeld geen controle zit of je dergelijke "hoge" rechten wel zomaar
mag uitdelen, ook al ben je misschien wel de admin.

De oorzaak ligt er in dat er in dit geval gekozen is om maar één permissie toe te kennen voor de gehele user administratie. 
Wellicht is het beter om die fijnmaziger te maken:

* permissie om gebruikers op te zoeken en in te zien
* permissie om gebruikers aan te passen
* permissie om permissies aan rollen toe te kennen

## Opdracht 8 

Als jij beheerder bent

1. Stel jij bent beheerder van deze school. Je kunt deze rapportages gebruiken om de organisatie beter te beveiligen.
   Aan wie ga je deze rapportages sturen?
2. Wie zou er in actie moeten komen bij afwijkingen?


## Opdracht 10

Bij het veranderen van de functie zal het User Provisioning proces netjes de oude lidmaatschappen eerst opruimen, de 
account verplaatsen naar de juiste plaats en dan de juiste default rollen weer toekennen. Ze verliest hierdoor dus bepaalde
rollen die ze mogelijk extra had. We hadden net zo arbitrair kunnen kiezen om de oude rollen te behouden, maar dat strookt 
niet altijd met wat logisch is. Immers vanuit haar rol had ze recht op vooraf gedefinieerde toegang. Eventueel later
toegekende rollen moeten dus eigenlijk opnieuw bekeken worden.

Stel dat je eerst bij HRM werkte en nu docent wordt bij een opleiding. Daarnaast zat je in een HRM-project. Het ligt 
voor de hand dat je de toegang tot specifieke HRM-systemen verliest. Het is onwenselijk dat je bijvoorbeeld als docent
nog bij HRM-systemen kunt voor opvragen en wijzigen van collega's. Echter, geldt dat ook voor het project? Wellicht
is het verstandig dat de projectleider dit opnieuw bekijkt. 

Mogelijk is er ook een overgangsregeling. Dus je blijft nog een tijdje in een dubbel rol: je ondersteunt de collega
die jouw baantje bij HRM overneemt nog een tijdje en daarom moet je nog toegang houden.

Zoals je ziet is het schrijven van een User Provisioning proces nog niet zo eenvoudig. Het begint dan ook vooral bij het
vooraf opstellen van procedures en een exacte definitie van wat je precies wilt!

