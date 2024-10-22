## Stargate Database

This is a Laravel application designed to serve as an initial model for the Stargate LARP Season 2 character database.
It is seeded with tables representing the rules.

- Up to date with S2 rules v1.1 mechanics

## Design Intent

- All tables are linked together with foreign keys.
- Changes to a character are stored as log entries
- Log entries are then consolidated into a character's current state
- Skill costs are determined based on the `cost` column in the `skills` table, then the `cost` column in the `skill_categories` table, then adjusted based on the value of `scaling` in `skill_categories` and the number of other skills bought from that category
- Repeatable skills are tracked in the `character_skills` table. Each time it is completed, a new entry is added if trained again. 

### Example

1. Bob trains Cryptography in downtime
2. This creates a `character_skills` entry for Bob's character to show that Bob is learning it
3. This also creates a `character_logs` entry for the training with the details of the training (months spent, whether there was a trainer, etc)
4. As a scaling skill, the total cost is determined by the number of entries from the `character_skills` table in the same category that are `completed = true`
5. If this training entry results in Cryptography being completed, the `character_skills` entry is updated to reflect this
6. Completed entries in `character_skills` are then consolidated into the character's current state
7. Bob's Cryptography card count is based off of the total of cards available from all skills that have Cryptography cards (as per `card_type_skill` table) that are not basic skills (determined by the `total` column)
8. Because Cryptography offers Cryptographic Insight, that is also added to Bob's character

### To-do

- [ ] Add GUI for character creation and editing
- [ ] Add GUI for character viewing
- [ ] Add GUI for log viewing
- [ ] Add skill descriptions
- [x] ~~Add feat descriptions~~ - Ki 2024-10-22
- [ ] Add user registration system
- [ ] Add permissions system

## Installation

1. Clone the repository
2. Set up your local environment
3. Run `composer install`
4. Run `php artisan migrate --seed`

## Contributing

A useful focus for contributions at the moment would be filling in the description fields for skills in `database/seeders`. Markdown will be available.

Once the GUI is in place, testing it and raising issues would be appreciated.
