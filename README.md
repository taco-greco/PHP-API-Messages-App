
# PHP API 

## Database

### Créer la base de données
```sql
CREATE DATABASE chat_application;
USE chat_application;
```
### Créer la table Users
```sql
CREATE TABLE `users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Created_At` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Online` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
);
```
### Créer la table Messages
```sql
CREATE TABLE `messages` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Sender_ID` int NOT NULL,
  `Receiver_ID` int NOT NULL,
  `Content` text COLLATE utf8mb4_general_ci NOT NULL,
  `Timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Is_Read` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Sender_ID` (`Sender_ID`),
  KEY `Receiver_ID` (`Receiver_ID`)
) 
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

