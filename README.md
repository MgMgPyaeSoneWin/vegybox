Langauge File is updated Completely.

FAQ_Entry
- From System Controller
    Function is Defined as:
        - Controller -> function insert_faq_lang() @ line Number 179 / No Parameters requied.
        - Model -> System_model -> One more value is added to array of $box_data @ line Number / 141
        - View -> is faq_entry_view_myanmar.php


        Table Structure
        - faq_id -> bigint(11)
        - question -> varchar(100)
        - answer -> mediumtext
        - status -> enum('enabled', 'disabled')
        - date -> datetime
        - language_type -> enum('english', 'unicode', 'zawgyi')


EditBox_Entry
- From Product Controller
    Function is Defined as:
        - Controller -> function function insert_box_lang() @ line Number 132 / No Parameters requied.
        - Model -> Product_model -> One more value is added to array of $box_data @ line Number / 59
        - View -> View is edit_box_myanmar.php

         Table Structure
         - box_id -> bigint(11)
         - name -> varchar(35)
         - description -> mediumtext
         - price -> int(8)
         - image -> varchar(35)	
         - status -> enum('enabled', 'disabled')
         - language_type -> enum('english', 'unicode', 'zawgyi')


AdditionalItem_Entry
- From Product Controller
    Function is Defined as:
        - Controller -> function function insert_item_myanmar() @ line Number 304 / No Parameters requied.
        - Model -> Product_model -> One more value is added to array of $item_data @ line Number / 149
        - View -> View is item_entry_view_myanmar.php

          Table Structure
         - item_id -> bigint(11)
         - name -> varchar(35)
         - description -> mediumtext
         - type -> varchar(50)
         - status -> enum('enabled', 'disabled')
         - image -> varchar(150)
         - net_weight -> varchar(35)
         - price -> int(8)
         - parent -> int(2)
         - language_type -> enum('english', 'unicode', 'zawgyi')


         Main.php -> index and public function faq()

		