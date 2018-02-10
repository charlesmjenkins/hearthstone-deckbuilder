# Hearthstone Deckbuilder App

- **[Try the Web App](http://charlesmjenkins.com/deckbuilder/login.php)** 
- **Description:** An online card game deck builder web application
- **Technologies:** MySQL, PHP, HTML5, CSS, JavaScript, jQuery
- **Team Project?:** No
- **My Lead Contributions:** Everything
- **Note:** For security purposes, database login credentials, API keys, etc. have been omitted from the code uploaded here.

## How to Execute:
- Click the link above
- Recommended browser: Google Chrome

## Overview
This project is a deck building application for the online collectible card game Hearthstone. The basic premise of the game is that two players each create a deck of monster and magic cards based around a hero class and then take turns strategically trying to destroy the other's hero. There is a large variety of cards that allows all sorts of interesting strategies to take shape as decks are put together. The application allows a user to create an account, log in, create and fiddle with his or her own deck creations, and save them for later, with access to all the cards in the game (at the time this was built in the fall of 2014). This helps users visualize and conveniently keep track of in-work or favorite decks, without having to always rely on being on a computer with the real game installed.

## Database Schema Description:
This database models a Hearthstone deckbuilding application.

The application has users, each with a username and password and uniquely identified by a user ID. All of these fields are required for a user. The password will always be a sha256 hash code which is 64 characters long.

There are classes in the game which must have a name and are uniquely identified by a class ID.

There are cards which are uniquely identified by a card ID string that corresponds to its ID in the actual game. These cards have a name (required), type (Minion, Spell, etc.) [required], rarity (Common, Rare, etc.), mana cost to play, attack score, health score, durability score, and card text explaining the various effects of the card. Cards are deemed either collectible actually in the game or not. Cards can be elite or not (which dictates how many copies can appear in a deck). Finally, cards can be assigned a class. Cards can have one class, but classes can apply to many cards.

The application allows one to build decks. Decks are uniquely identified by a deck ID. Decks must have a name, a class, and a user assigned to it. Decks must have one class, but classes can apply to many decks. Each deck must belong to a single user, but a user can have many decks. Each deck can have many cards, and cards can belong to many decks.

![ER Diagram](/screenshots/er-diagram.png?raw=true)

![Database Schema](/screenshots/schema.png?raw=true)

## Screenshots

![Login Screenshot](/screenshots/login.png?raw=true "Login")

![Deck Builder Main Page Screenshot](/screenshots/deckbuilder.png?raw=true "Deckbuilder Main Page")

![Deck Edit Screenshot](/screenshots/deckedit.png?raw=true "Deck Editor")

![Class Select Screenshot](/screenshots/classselect.png?raw=true "New Deck Class Select")

## Acknowledgements

**http://hearthstonejson.com/** - I found this fanmade collection of Hearthstone card data JSON files that were pulled directly from the game's files. I modified and merged various collections of cards into one JSON file and then wrote a php script to parse out the fields most applicable to my application and insert them into the card table of my database. This gave my application rich, real, accurate data that allows my application to stack up somewhat with other Hearthstone deckbuilders. It also facilitated being able to dynamically pull card images for every card by having all the actual card IDs.

I wrote (and included in this repository) a script called insert_cards.php that parses this JSON card data and inserts it into the database.

**http://iamceege.github.io/tooltipster/** - I used this jQuery tooltip plugin to both display jQuery Validation error messages as well as display images of cards dynamically based on the card's ID.

**http://www.chartjs.org/** - I used Chart.js, which uses the HTML5 canvas to display dynamic charts and graphs, to create a bar chart that shows the distribution of card mana cost in a user's deck.