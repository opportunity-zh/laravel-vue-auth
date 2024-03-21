# Laravel Vue

Eine Vorlage für die Verwendung von Laravel mit vue.js.

## Vorbereiten

### 1. Repository herunterladen oder Projekt selbst einrichten

#### 1. Herunterladen

Lade das Repository auf Deinen Computer herunter, benenne es um und verschiebe es in Deinen Projektordner.

#### 2. Selber einrichten

Wenn Du selber alles einrichten willst, um einen genaueren Überblick zu erhalten, kannst Du Dir folgende Links anschauen:

-   [Laravel mit vue.js einrichten](https://vueschool.io/articles/vuejs-tutorials/the-ultimate-guide-for-using-vue-js-with-laravel/)
-   [Authentication für SPA einrichten](https://laravelvuespa.com/)

### 2. Dependencies installieren

#### 1. NPM Packages

Öffne das Projekt in VS Code, öffne das Terminal und verwende den folgedenden Befehl um alle Dependencies (Packages) zu installieren.

```bash
npm install
```

#### 2. Composer Packages

Im selben Terminal kannst Du auch gleich die Composer Packages installieren, indem Du folgenden Befehl eingibst.
Dieser Befehl erstellt einen minimal konfigurierten Docker Container, um die Composer Packages zu installieren.

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Environment Variablen anpassen

Nimm das **.env.example** File und benenne es um in **.env**. Wenn Du es öffnest, siehst Du, dass der **APP_KEY noch leer** ist. Mit folgendem Befehl kannst Du dir einen Key generieren, damit alles funktioniert.

```bash
php artisan key:generate
```

## Starten

### 1. Docker Container hochfahren

Wenn Du in Laravel mit Docker arbeitest, kannst du statt dem Befehl docker compose den Befehl './vendor/bin/sail up' verwenden. Evtl. ist auf Deinem Computer bereits ein alias hinzugefügt worden, deshalb kannst Du die Kurzform davon verwenden:

```bash
sail up
```

### 2. NPM Server starten

```bash
npm run dev
```

### 3. Website aufrufen

Unter localhost kannst Du nun die Website anschauen.

```bash
http://localhost
```

### 4. PHPMyAdmin aufrufen

Unter localhost:8080 findest Du PHPMyAdmin. Logindaten findest Du im .env File.

```bash
http://localhost:8080
```

### 5. Postman für Testing einrichten

Damit Du die authorisierten API Routes testen kannst, ist es sinnvoll, Postman dafür einzurichten. Weil die Authentifizierung über Cookies erfolgt, die bei jedem Request mitgesendet werden müssen, ist das etwas komplizierter.

#### 1. Vorbereigung

Überprüfe, ob Du wirklich einen User in Deiner Datenbank hast. Wenn nicht, füge einen hinzu. Entweder über den DatabaseSeeder oder mit Artisan Tinker. Falls Du in dieser Erklärung nicht weiter kommst, findest Du [hier](https://codecourse.com/articles/laravel-sanctum-airlock-with-postman/) mehr Details.

#### 2. Login einrichten

1. Erstelle einen Login (localhost/api/login) POST Request in Postman
2. Öffne den Header Reiter und füge folgende Headers ein
    - Content-Type : application/json
    - Accept: application/json
3. Öffne den Body Reiter und wähle aus: **raw** und **JSON**
4. Füge folgenden JSON String ein:

```json
{
    "email": "test@opportunity-zuerich.ch",
    "password": "password"
}
```

5. Öffne den Reiter pre-request Script und füge folgendes Script ein

```javascript
pm.sendRequest(
    {
        url: "http://localhost/sanctum/csrf-cookie",
        method: "GET",
    },
    function (error, response, { cookies }) {
        if (!error) {
            pm.environment.set("xsrf-token", cookies.get("XSRF-TOKEN"));
        }
    }
);
```

Dieses Script hilft Dir dabei, dass die mitgesendete CSRF Cookie in einer Environmentvariable gespeichert wird.

6. CSRF-Token bei jedem Request mitsenden  
   Der Token wurd nun als Environmentvarible unter dem Namen **xsrf-token** gespeichert. Füge diesen nun als folgenden Header hinzu:

-   X-XSRF-TOKEN: {{xsrf-token}}

## Fehlerbehebung

### 1. Ports besetzt

Das kann passieren, wenn die Ports, die Docker in den Containern benutzen will, diese jedoch vom System bereits besetzt sind.

Beispielsweise: listen tcp4 0.0.0.0:80: bind: address already in use

1. Prozess finden
   Dann musst Du herausfinden, von welchem Prozess diese verwendet werden und diesen Prozess dann beenden. Das machst Du mit folgenden Befehlen. Ersetze dabei **PORT** durch den besetzten Port. Im oberen Beispiel wäre das **80**

```bash
sudo netstat -laputen | grep ':PORT'
```

2. Prozess beenden
   Wenn Du den Prozess gefunden hast, welcher den Port besetzt, findest Du neben dem Namen des Prozesses eine Zahl, welches die **Prozess-ID** ist. Den Prozess kannst Du mit folgendem Befehl beenden. Ersetze <id> mit der tatsächlichen Prozess-ID

```bash
sudo kill <id>
```

Wenn Du das gemacht hast, verwenden zuerst den folgenden Befehl und versuche es erst dann wieder mit sail up

```bash
sail down
```

### 2. Dockerprobleme

Wenn Du Probleme mit Docker bzw. noch laufenden Containern hast, kannst Du diese Container beenden und auch direkt aus dem ... löschen.

Das machst Du mit folgenden Befehlen

1. Alle laufenden Container beenden

```bash
docker stop $(docker ps -a -q)
```

2. Alle gestoppten Container entfernen

```bash
docker rm $(docker ps -a -q)
```

### Storage Folder Permission Problem

1. Versuche sail up ohne ache zu starten

```bash
sail build --no-cache
```

Falls das nicht funktioniert, versuche die Berechtigungen des storage Ordners zu ändern. Im Projektordner:

```bash
sudo chmod -R 777 storage
```
