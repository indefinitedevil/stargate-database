## Stargate Database

This is a Laravel application designed to serve as an initial model for the Stargate LARP Season 2 character database.
It is seeded with tables representing the rules.

- Up to date with S2 rules v1.4 mechanics as of 27th October 2025

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

New features and issues are tracked in the [Issues](https://github.com/indefinitedevil/stargate-database/issues) tab on GitHub.

## Installation

1. Clone the repository
2. Set up your local environment (ddev recommended)
   1. If running ddev, run the following commands within the ddev shell (`ddev ssh`) or add `ddev ` before the commands to have ddev do that
3. Run `composer install`
4. Run `php artisan key:generate` to generate a new key for your local environment or get one from an existing environment (existing key needed for using database dumps without issues)
5. Run `php artisan migrate --seed` to install/migrate database tables and any data seeding
   1. Also run `php artisan migrate` after any update that involves database changes
6. Run `npm install` then `npm run build` to incorporate any changes to the front end

## Contributing

Testing, raising issues, and helping out with the code is always welcome. If you have any questions, please reach out to Eligos on Discord (moonemprah) or raise an issue on GitHub.
