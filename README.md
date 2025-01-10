
# DnD Database (DNDDB) 🎲

DnD Database (DNDDB) is a PHP-based web application designed to assist players and Dungeon Masters in managing their Dungeons & Dragons campaigns, characters, and spells. It uses MySQL for data storage and provides a user-friendly interface for managing game-related data.
## Project Goal 🎯

This project was developed with the goal of practicing and improving my skills in **PHP** and, particularly, in implementing **three-tier architecture** (Model, Controller, Service, and Handler). The code structure and design are organized according to this architectural pattern to improve maintainability, scalability, and separation of concerns in the code.

### Three-Tier Architecture 🏗️

The architecture used in this project follows an **extended Model-View-Controller (MVC)** pattern with an additional **Service and Handler layer**, aimed at separating the different responsibilities of the application. Below is a breakdown of each layer:

- **Model**: Represents the application's data structure and contains logic related to the database. It is the layer that directly interacts with MySQL to perform CRUD operations (create, read, update, delete).
  
- **Controller**: Handles user requests and coordinates the interaction between the model and the views. Controllers take requests from the user interface, pass the data to the model for processing, and then send the response back to the views.
  
- **Service**: Services manage business logic and more complex operations. They are designed to contain the core logic of the application, such as handling campaigns or characters in the context of this project.

- **Handler**: Handlers are responsible for managing interactions with the database or external services. Often, handlers encapsulate database operations (e.g., inserting, updating, deleting) and make them accessible via the services.

### Educational Purpose 📚

The primary purpose of this project is to **practice implementing a clean and scalable architecture** in PHP. By structuring the application with these layers, the code is more modular, easier to maintain, and easier to extend in the future. Additionally, this approach allows for a clear separation between business logic and presentation, making it easier to test and improve each layer independently.

## Features ✨

- **Campaign Management**: Create, edit, view, and delete D&D campaigns.
- **Character Management**: Add, manage, and delete characters, including their associated spells.
- **Spell Management**: Create and maintain a library of spells.
- **Authentication**: User registration and login for secure data access.
- **Interactive UI**: Responsive HTML, CSS, and JavaScript front-end.
- **Media Integration**: Includes music, video, and images for an immersive experience.

## Project Structure 📂

```plaintext
DNDDB/
├── config/                  # Configuration files (e.g., database connection)
├── database/                # MySQL scripts to set up the database
├── public/                  # Public-facing PHP scripts and assets
│   ├── assets/              # Icons, music, and videos
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript scripts
│   ├── *.php                # Functional scripts (e.g., login, character management)
├── src/                     # Backend structure (MVC components)
│   ├── Controller/          # Controllers for handling requests
│   ├── Handler/             # Handlers for database operations
│   ├── Model/               # Database models
│   ├── Service/             # Services for business logic
├── uploads/                 # Uploaded media files
└── LICENSE                  # MIT license file
└── README.md                # Project documentation
```

## Installation 🛠️

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Rachni/dnd-web-database.git
   ```

2. **Set Up the Database**
   - Import the SQL files from the `database/` directory into your MySQL server in the following order:
     1. `01-CREATEdatabase.txt`
     2. `02-CREATEusers.txt`
     3. `03-INSERTdata.txt`

3. **Host the Application**
   - Use a local PHP server or deploy it to a web server:
     ```bash
     php -S localhost:8000 -t public
     ```

## Usage 🚀

- **Access the Web App**: Open your browser and go to `http://localhost:8000`.
- **Login/Register**: Create a user account or log in to manage your campaigns, characters, and spells.
- **Media Content**: Explore the assets (icons, music, videos) included for an immersive D&D experience.

## Dependencies 📦

- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **JavaScript**: Included in the `public/js` folder
- **HTML/CSS**: Responsive front-end design

---

**TODO** 🔨

- **Improve Character Attributes**: Enhance character profiles to include more D&D attributes (e.g., Strength, Dexterity, Constitution, Intelligence, Wisdom, Charisma, and others).
- **Dice Rolling Functionality**: Implement a feature for dice rolls (such as rolling for attack, damage, and saving throws).
- **UI Enhancement**: Apply a frontend style similar to the login and registration pages to the entire site for consistency and improved user experience.
- **Spell Management**: Enable the ability to link spells to specific characters. 
  - Show which spells are linked to which characters.
  - Implement functionality to delete spells from characters or edit the list of linked spells.
  - Create DEMO.

---

**Enjoy managing your D&D campaigns with DnD Database! 🏰**
