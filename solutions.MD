# Uitwerkingen

Hier vind je de uitwerkingen van de opgaven.



## Opdracht 1

Door in te loggen op de LDAP-administratie kan gekeken worden naar de structuur van de account administratie.
Het personeel van Marketing is geen onderwijzend personeel dus valt onder Staff/Marketing.

![opdracht 1 - 01.png](images/excersises/opdracht%201%20-%2001.png)

De aanname is dat je verwacht dat deze groep gebruikers inderdaad bij de applicatie voor Marketing website hoort. Dat
kun je alleen zeker weten door het aan de programmeur of een website beheer te vragen of in de documentatie te kijken.

In dit geval kun je het afleiden uit de configuratie van de webserver. Kijk op Github op de volgende locatie:

* [marketing.conf](webserver/conf/marketing.conf) (webserver --> conf --> marketing.conf)

En zoek naar het onderstaande configuratie item:

```apacheconf
 Require ldap-group cn=Marketing,ou=roles,dc=NHLStenden,dc=com
```

Daar staat netjes dat het intranet alleen beschikbaar is voor de genoemde groep. 

## Opdracht 2


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


