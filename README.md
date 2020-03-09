# Studies: Laravel API 
Build an API with Laravel - Thomas Gamborg

## Prospect

Anna’s Bookstore has existed since the early 90s where it opened as a small corner shop in the city. It isn’t a fancy place — the floor, bookshelves and furniture make it seem a bit like your grandma’s place, but it’s cosy,welcoming and the perfect combination of a bookstore and coffee shop, where you will feel right at home. Customers come back for Anna’s personality, which is reflected in every little thing in the store, and especially the selection of books. It’s not the selection of books you’ll find in every store; these are hand-picked by Anna herself. She’ll often offer customers a cup of coffee and discuss a book, or the work of an author, when there isn’t anything to do at the cash register. You can feel that both literature and people are her passion.

As the years go by, new stores start emerging and Anna gets a lot more competition, especially from modern bookstores, where people can find and buy books faster. This isn’t really Anna’s cup of tea, and to compete, she would have to change everything about her store. She’s beginning to think about closing the store, until a day when her nephew visits her. When they talk about the store, the nephew asks: “Why don’t you go online, so you can keep your store as it is and sell to people at home or in a hurry?”. Anna must admit that it is a good idea. Over the years, she has started to order books online herself and is quite pleased with the experience. The nephew then continues: “You don’t have to have a huge selection of the books, why not cater to a niche and sell a curated selection of books, like you do in your store now? You know people always praise you for having so many hidden gems.” Anna is convinced. This is something she can see herself in, she can still use her expertise, and people can still buy a bit of Anna’s personality in the selection of her books.

Other than being able to sell books and being able to keep stock of her books, Anna wants to be able to present these books through authors. Anna has a bunch of authors that she can vouch for, who always publish good work, a key part of her having so many hidden gems. 

Sometimes, Anna finds books by reading comments that other people have left on the online bookstores. She loves the helpful tips people give in the comments and it reminds her of the discussions she has been apart of in her own bookstore. She would like something like this in her online bookstore as well.


## Requirement Specification

- Main Areas: Public Store, User Profile and Administration

- Requirements:
    - The administrator should be able to enter an email address
        - The email should be of the bookstore’s own domain to be allowed as administrator
        - The email cannot also be used for a regular user profile.
    - The administrator should be able to enter a password
        - Passwords should be over 6 characters
        - Passwords should contain at least one capital letter
        - Passwords should contain at least one number
    - The administrator should be able to tick a “remember me” checkbox
        - When ticking the remember me checkbox, a cookie should be saved for easier logins in the future
        - A “remember me” cookie should only be valid for 48 hours
    - The administrator should be able to click the login button to proceed
    - The administrator should be able to see if he/she has entered the email or password incorrectly.

- Scenario:
    - The administrator has arrived to the login screen. She types in her email in the email section and pushes the tab button on her keyboard to get down into the password space, where she types in her password as well. Before clicking the login button, she ticks off the “remember me” checkbox so she doesn’t have to perform the same procedure, if she comes back within the next 48 hours.

- Resources:
    - Books
        - [x] Attributes:
            - title
            - description
            - publication_year
        - [x] Relationships:
            - Authors N-N
                - SELF -> GET: /books/1/relationships/authors
                - RELATED -> GET: /books/1/authors
            - Comments 1-N
                - SELF -> GET: /books/1/relationships/comments
                - RELATED -> GET: /books/1/comments
    - Authors
        - [x] Attributes:
            - name
            - created_at
            - updated_at
        - [x] Relationships:
            - Books N-N
                - SELF -> GET: /authors/1/relationships/books
                - RELATED -> GET: /authors/1/books
    - Comments
        - [x] Attributes:
            - message
        - [x] Relationships:
            - Books 1-N
                - SELF -> GET: /comments/1/relationships/books
                - RELATED -> GET: /comments/1/books
            - Users 1-1
                - SELF -> GET: /comments/1/relationships/books
                - RELATED -> GET: /comments/1/books
    - Users
        - [x] Attributes:
            - username
            - email
            - password
        - [x] Relationships:
            - Comments 1-1
                - SELF -> GET: /users/1/relationships/comments
                - RELATED -> GET: /users/1/comments
    - Roles

## Studies
- These studies were made during the read of [Build an Api with Laravel](https://buildanapi.com/).
- You can contact me [here](https://rafaelcasachi.dev/?from=github).
