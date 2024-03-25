# Laravel Vue Auth

Eine Vorlage für die Verwendung von Laravel mit vue.js inkl. Authentication.

## Vorbereiten

### 1. Repository herunterladen oder Projekt selbst einrichten

#### 1. Herunterladen

Lade das Repository auf Deinen Computer herunter, benenne es um und verschiebe es in Deinen Projektordner. Gehe alle Punkte Schritt für Schritt durch.

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

Um die authorisierten API Routes zu testen, kannst Du Postman einrichten.

#### 1. Vorbereitung

Überprüfe, dass Du noch einen User in Deiner Datenbank hast. Wenn nicht, füge einen hinzu. Führe dazu einfach den Seeder aus (sail artisan db:seed).

Falls Du in der folgenden Erklärung nicht weiter kommst, findest Du [hier](https://codecourse.com/articles/laravel-sanctum-airlock-with-postman/) eine detaillierte Erklärung inkl. Bilder (auf Englisch).

#### 2. Login einrichten

1. Erstelle einen **POST Request** mit der URL **localhost/api/login** in Postman
2. Öffne den **Header Reiter** und füge folgende Headers ein
    - Content-Type : application/json
    - Accept: application/json
3. Öffne den **Body** Reiter und wähle aus: **raw** und **JSON**
4. Füge folgenden **JSON** String ein:

```json
{
    "email": "test@opportunity-zuerich.ch",
    "password": "password"
}
```

5. Öffne den Reiter **pre-request Script** und füge folgendes Script ein

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

Dieses Script hilft Dir dabei, dass das beim Login erhaltene **CSRF-Cookie** in einer **Environmentvariable** gespeichert wird und in allen anderen Requests verwendet werden kann.

6. CSRF-Token bei jedem Request mitsenden  
   Der Token wurd nun als Environmentvarible unter dem Namen **xsrf-token** gespeichert. Füge diesen nun folgendermassen als Header hinzu:

    - X-XSRF-TOKEN: {{xsrf-token}}

7. Klicke **noch nicht auf "Send"**, damit Du noch nicht angemeldet bist

#### 3. Testroute aufrufen

Im nächsten Schritt rufst Du eine Testroute auf, die nur aufgerufen werden kann, wenn man sich authentifiziert (angemeldet hat)

1. Login Request duplizieren  
   Klicke mit der rechten Maustaste oben auf den Tab, in welchem sich der Login Request befindet und wähle aus "Duplicate Tab"

2. Request anpassen  
   Du hast jetzt zwei Login Requests. Passe den zweiten folgendermassen an:

    - Ändere die Methode auf GET
    - Ändere die URL auf **localhost/api/users/auth**

3. Referer Header einfügen  
   Laravel überprüft auch bei jeder Anfrage den **referer Header**. Diesen musst Du zusätzlich in den Header einfügen, um einen authenticated Request machen zu können. Füge also folgenden Header hinzu:

    - Referer: localhost

4. Request absenden  
   Versuche nun den Request an die bereits vorhanden Route abzusenden. Du solltest folgenden Fehler erhalten:

```json
{
    "message": "Unauthenticated."
}
```

5. Anmelden  
   Dieser Fehler teilt Dir mit, dass Du nicht authentifiziert bist. Melde Dich an:

    - Gehe zum Login Request
    - Klicke auf send - Du bist jetzt angemeldet
    - Gehe zurück zum Testrequest
    - Klicke auf Send und du solltest folgendes erhalten:

```json
{
    "id": 1,
    "name": "Hans Mustermann",
    "email": "test@opportunity-zuerich.ch",
    "email_verified_at": "2024-03-21T09:05:56.000000Z",
    "two_factor_secret": null,
    "two_factor_recovery_codes": null,
    "two_factor_confirmed_at": null,
    "created_at": "2024-03-21T09:05:56.000000Z",
    "updated_at": "2024-03-21T09:05:56.000000Z"
}
```

## Ready

Du bist jetzt bereit, um Deine Website Besuch zu authentifizieren.

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

### 3. Storage Folder Permission Problem

1. Versuche sail up ohne ache zu starten

```bash
sail build --no-cache
```

Falls das nicht funktioniert, versuche die Berechtigungen des storage Ordners zu ändern. Im Projektordner:

```bash
sudo chmod -R 777 storage
```

## Wichtige Links

-   [Example](https://github.com/garethredfern/laravel-vue/tree/main/src)
-   [vue.js Documentation](https://vuejs.org/guide/introduction)
-   [Pinia Documentation](https://pinia.vuejs.org/)
