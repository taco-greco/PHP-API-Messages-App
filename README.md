
# PHP API 

## Database

### Créer la base de données
```sql
CREATE DATABASE chat_application;
USE chat_application;
```
### Créer la table Users
```sql
CREATE TABLE Users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Last_Online TIMESTAMP DEFAULT NULL
);
```
### Créer la table Messages
```sql
CREATE TABLE Messages (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Sender_ID INT NOT NULL,
    Receiver_ID INT NOT NULL,
    Content TEXT NOT NULL,
    Timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Is_Read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (Sender_ID) REFERENCES Users(ID) ON DELETE CASCADE,
    FOREIGN KEY (Receiver_ID) REFERENCES Users(ID) ON DELETE CASCADE
);
```
### Créer la table Conversations
```sql
CREATE TABLE Conversations (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Participant_1_ID INT NOT NULL,
    Participant_2_ID INT NOT NULL,
    Last_Message_ID INT DEFAULT NULL,
    Last_Updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Participant_1_ID) REFERENCES Users(ID) ON DELETE CASCADE,
    FOREIGN KEY (Participant_2_ID) REFERENCES Users(ID) ON DELETE CASCADE,
    FOREIGN KEY (Last_Message_ID) REFERENCES Messages(ID) ON DELETE SET NULL,
    CONSTRAINT Unique_Conversation UNIQUE (Participant_1_ID, Participant_2_ID)
);

```

