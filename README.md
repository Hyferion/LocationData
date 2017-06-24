LocationData - Einführungsprojekt
=================================

Dies ist ein Plgun für die Einführung in die ILIAS-Plugin-Entwicklung der studer + raimann ag.
 
# Einrichtung
Dies ist das Basis-Repository, dies dient nur als Vorlage. Neue Entwickler legen 
eine Kopie davon an. Dabei wird mit Git entsprechend ein neues Reposity mit 
einer Kopie angelegt:

In der Konsole auf das Basis-verzeichnis der ILIAS-Installation wechseln

```bash
cd /var/www/ilias
```

Das Basis-Repository wird geklont: 

```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject/  
cd Customizing/global/plugins/Services/Repository/RepositoryObject/  
git clone https://git.studer-raimann.ch/35_Entwicklung/LocationData.git LocationData
```

In das Repository wechseln und eine neue URL für das eigene Repository setzen, 
wobei als Gruppe der jeweilige Gitlab-Benutzername verwendet wird, bspw. fschmid. 
Das Repo muss zuerst via https://git.studer-raimann.ch angelegt werden (Projects -> New Project).

```bash
cd LocationData
git remote set-url origin https://git.studer-raimann.ch/fschmid/LocationData.git
```

Anschliessend wird das gesamte Repository gepusht:

```bash
git push origin --all
```
# Entwicklung
