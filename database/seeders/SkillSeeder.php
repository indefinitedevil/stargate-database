<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedSkillCategories();
        $this->seedSkills();
        $this->seedSkillSpecialties();
        $this->seedSkillPrereqs();
        $this->seedSkillLockouts();
        $this->seedSkillDiscounts();
        $this->seedSkillTraining();
        $this->seedCardSkills();
        $this->seedBackgroundSkills();
    }

    public function seedSkillCategories()
    {
        DB::table('skill_categories')->upsert([
            [
                'id' => SkillCategory::TECHNOLOGY,
                'name' => 'Technology',
                'cost' => 12,
                'scaling' => 1,
            ],
            [
                'id' => SkillCategory::SCIENCE_SOCIAL,
                'name' => 'Science &amp; Social',
                'cost' => 9,
                'scaling' => 1,
            ],
            [
                'id' => SkillCategory::COMPLEX,
                'name' => 'Complex',
                'cost' => 6,
                'scaling' => 0,
            ],
            [
                'id' => SkillCategory::BASIC,
                'name' => 'Basic',
                'cost' => 3,
                'scaling' => 0,
            ],
            [
                'id' => SkillCategory::COMBAT,
                'name' => 'Combat',
                'cost' => -1,
                'scaling' => 0,
            ],
            [
                'id' => SkillCategory::ALIEN,
                'name' => 'Alien',
                'cost' => -1,
                'scaling' => 0,
            ],
            [
                'id' => SkillCategory::SYSTEM,
                'name' => 'System',
                'cost' => 0,
                'scaling' => 0,
            ],
        ], 'id');
    }

    public function seedSkills()
    {
        $this->seedTechnologySkills();
        $this->seedSocialScienceSkills();
        $this->seedComplexSkills();
        $this->seedBasicSkills();
        $this->seedCombatSkills();
        $this->seedAlienSkills();
        $this->seedSystemSkills();
    }

    public function seedTechnologySkills()
    {
        DB::table('skills')->upsert([
            [
                'id' => 1,
                'name' => 'Computing',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'The study of computer systems. This skill allows you to hack into other operating systems and develop new systems yourself as well as countermeasures towards hackers.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Cryptography',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'Cryptography is the study of codes, ciphers, and hidden meanings, used for code breaking.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Electrical Engineering',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'This deals with the study and applications of electricity, electromagnetic forces, and electronics. Also deals with power, control systems, integrated circuits, and circuit boards.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Explosives Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'Expertise in making and disposing a variety of devices aimed at blowing things up. Grants the knowledge of where to place a device for maximum effect. This is sometimes used in conjunction with other skills to make elaborate devices that may have more damaging effects than listed in the Explosives Table - speak to an event ref if you wish to attempt this.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Larceny',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'Larceny is the ability to breach physical security measures. You can pick locks, bypass security measures, hotwire cars, open locked doors etc.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Mechanical Engineering',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'This deals with Thermodynamics and Kinematics. This skill is fundamental in the design, manufacture, and maintenance of mechanical systems, vehicles, industrial equipment & robotics.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Paramedic',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'You are a trained paramedic. You can treat and assist in the treatment of medical injuries.

* **Medical Abilities:** Stabilise Casualty, Read All Wound Tokens, Prep for Movement
* **Treatable Keywords:** First Aid, Minor Procedure
* **Assisting Keywords:** All',
                'abilities' => 'Stabilise Casualty (10s),Read All Wound Tokens,Prep for Movement (60s)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Signals Intelligence (SIGINT)',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::TECHNOLOGY,
                'description' => 'The study, use and maintenance of telecommunications, monitoring & satellite technology. This also grants the knowledge required for the development of new signalling devices.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
        ], 'id');
    }

    public function seedSocialScienceSkills()
    {
        DB::table('skills')->upsert([
            [
                'id' => Skill::ARCHEO_ANTHROPOLOGY,
                'name' => 'Archeo-Anthropology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'You hold a qualification in Archaeology or Anthropology. You understand:
* The development of human societies across Earth.
* How to identify and recognise the artefacts, myths, and cultures of our ancestors.
* How to recognise the cultural roots and ethos of an alien world’s history.
* How to identify and recognise the key elements of alien languages. (This does not grant the ability to read languages beyond those you are skilled in.)

Characters with Archaeo-Anthropology must also select two specialist cultures. Additional Specialty areas may be learned as a Complex Skill, requiring 6 Months of Training. Having this skill may aid you with assimilating into an Alien culture similar to one of your specialties, as if you were truly a native.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 2,
                'specialty_type_id' => 1,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 11,
                'name' => 'Astrophysics',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'This incorporates astronomy, the calculation of stellar distances and the exploration of other worlds. It also includes stellar navigation and stellar matter.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 12,
                'name' => 'Botany',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'Botany is the study of plant life. Botany covers a wide range of scientific disciplines that study plants, algae, and fungi including structure, growth, reproduction, metabolism, development, diseases, and chemical properties and evolutionary relationships between the different groups.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 13,
                'name' => 'Genetics & Evolutionary Biology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'Evolutionary Biology is the study of how living things have developed in response to their native environments. In addition to the study of Genetics, this field can be applied to determine how a living organism came to be and what sort of environments shaped it.

Conversely, it can also be used to theorise how an organism would adapt in response to a given environment.

This Skill reduces the time on all Medical Card problems by 10%. This stacks with Pathology to make a 20% reduction.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 14,
                'name' => 'Linguistics',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'The linguistics skill is the study and application of language. With this skill you can communicate effectively in any Earth language both spoken and written. Alien languages may become available to be gained in play at a future time.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 15,
                'name' => 'Material Science',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'The study of materials & their properties and uses. This includes Chemistry and Physics.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 16,
                'name' => 'Medical Doctor',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'You are a trained medical doctor. You can treat and assist in the treatment of medical injuries, using the RED times on the Medical Skill Game card.

* **Medical Abilities:** Stabilise Casualty, Read All Wound Tokens, Prep for Movement
* **Treatable Keywords:** All
* **Assisting Keywords:** All

This skill reduces the training time of Physiology to 3 months, regardless of how many other Science and Social skills you have.',
                'abilities' => 'Stabilise Casualty (10s),Read All Wound Tokens,Prep for Movement (60s)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 17,
                'name' => 'Pathology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'Pathology is the study and diagnosis of disease; this is done through the examination of organs, tissues, bodily fluids, and autopsies. It also encompasses the related scientific study of disease processes.

This Skill reduces the time on all Medical Card problems by 10%. This stacks with Genetics and Evolutionary Biology for a 20% total reduction.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 18,
                'name' => 'Physiology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'Physiology is the study of the mechanical, physical, and biochemical functions of animals, the study of anatomy and the interaction of cells. This skill combines both human and animal physiology.

This skill reduces the training time of Medical Doctor to 3 months, regardless of how many other Science and Social skills you have.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 19,
                'name' => 'Psychology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'You have spent time learning about human psychology, enabling you to both lie convincingly and detect when others are doing so. You can understand and anticipate actions and analyse the dysfunctional.

After role-playing with someone for at least five minutes you may spend 1 Vigor to do any and all of the following:
* See whether someone’s last statement was a lie.
* See whether someone in the general conversation is concealing something.
* Find out what someone is trying to achieve (in broad terms) through their words.
* Detect their current emotional state.

You may Spend 2 Vigor to falsify a response to a Psychological Challenge if these abilities are used against you.

After role-playing with someone for at least twenty minutes you may spend 2 Vigor to do the following:
* Detect someone’s psychological flaws.

You may spend additional points of Vigor to reduce the time needed to use these abilities, at a rate of 1 Vigor per five minutes, to a minimum of 1 minute.',
                'abilities' => 'Detect Lie (1V),Detect Concealment (1V),Detect Goal (1V),Detect Emotional State (1V),Detect Psych Flaws (2V),Falsify Psych Response (2V)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 20,
                'name' => 'Psychotherapy',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'You are able to provide comfort and support when your fellows need it most. After roleplaying with someone for at least five minutes outside of combat, you may Spend 1 Vigor to do either or both of the following.
* Refresh your target’s Vigor to full, regardless of their maximum Vigor. (This does not affect others with the Psychotherapy skill)
* Detect their current emotional state.

After role-playing with someone for at least twenty minutes you may spend 1 Vigor to do the following:
* Treat the Psychological element of a Wound token with the Psychology Category, restoring the patient to 1 Body.

You may spend additional points of Vigor to reduce the time needed to use these abilities, at a rate of 1 Vigor per five minutes, to a minimum of 1 minute.

Note: The requirement is time spent roleplaying with your target. The form of that roleplay could be a cup of tea with the padre, a chat with your best mate about how this is all a bit shit and you\'re knackered, going for a jog around the camp because the Sgt Major thinks exercise endorphins are the cure for all - while the skill is called psychotherapy because it interacts with the psychology wound tokens, the use in play can be tailored to your character type.',
                'abilities' => 'Refresh Vigor (1V),Detect Emotional State (1V),Treat Psych Wound (1V)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 21,
                'name' => 'Pure Mathematics',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'The study of numbers, algebra, geometry, statistics, and the flow of how numbers affect the world.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 86,
                'name' => 'Physiology (discounted)',
                'print_name' => 'Physiology',
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'Physiology is the study of the mechanical, physical, and biochemical functions of animals, the study of anatomy and the interaction of cells. This skill combines both human and animal physiology.

This skill reduces the training time of Medical Doctor to 3 months, regardless of how many other Science and Social skills you have.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 87,
                'name' => 'Medical Doctor (discounted)',
                'print_name' => 'Medical Doctor',
                'skill_category_id' => SkillCategory::SCIENCE_SOCIAL,
                'description' => 'You are a trained medical doctor. You can treat and assist in the treatment of medical injuries, using the RED times on the Medical Skill Game card.

* **Medical Abilities:** Stabilise Casualty, Read All Wound Tokens, Prep for Movement
* **Treatable Keywords:** All
* **Assisting Keywords:** All

This skill reduces the training time of Physiology to 3 months, regardless of how many other Science and Social skills you have.',
                'abilities' => 'Stabilise Casualty,Read All Wound Tokens,Prep for Movement',
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
        ], 'id');
    }

    public function seedComplexSkills()
    {
        DB::table('skills')->upsert([
            [
                'id' => Skill::ADDITIONAL_AA_SPEC,
                'name' => 'Additional Archeo-Anthrology Speciality',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'Having this skill may aid you with assimilating into an Alien culture similar to one of your specialties, as if you were truly a native.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 1,
                'specialty_type_id' => 1,
                'repeatable' => 20,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 22,
                'name' => 'Armorer',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You can repair damaged weapons and equipment. It is also possible for you to make weapons or ammunition if you have the required tools and supplies. (See an event referee).
* It takes 5 minutes to repair Primitive Weapons, or weapons with the Robust Trait.
* It takes 10 minutes to repair any weapon except Heavy Weapons
* It takes 20 minutes to repair Heavy Weapons',
                'abilities' => 'Repair Primitive Weapons (5m),Repair Equipment (10m),Repair Heavy Weapons (20m)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 23,
                'name' => 'Astronaut',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are adept at using spacesuits and manoeuvring in Zero-Gravity environments. When combined with Pilot, this grants the ability to pilot Earth Spacecraft such as the Space Shuttle. When Combined with Technology Skills, they may allow you to ignore relevant environmental penalties to skill games at Event Ref discretion.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 24,
                'name' => 'CasEvac Specialist',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'This Skill grants the ability ‘Prep For Movement’ if you do not already possess it.',
                'abilities' => 'Prep For Movement',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 27,
                'name' => 'Close Protection',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are a trained Bodyguard or have received other High Value Target (HVT) protection training.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 28,
                'name' => 'Dreaming Spires',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You have access to the best research establishments in the world, and the library card to prove it. You are a very well-known academic and during downtime you tend to learn pieces of information from the world of academia. Information gained in this way can refer to new technology discoveries and procedures, or academic rumours which may be helpful to your character.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 29,
                'name' => 'Forensic Science',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You understand the basics of forensic science.

You can also use forensics in conjunction with other skills you have to enable you to interpret information according to your skills.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 30,
                'name' => 'History and Philosophy',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You have a grasp of History and have a basic knowledge of the main events that have occurred throughout recorded history.

You have studied the art of thought, from the Greek philosophers to modern ethicists.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 31,
                'name' => 'Hypnosis',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are knowledgeable in Hypnosis as a subset of psychology. If the subject is willing, you may:
* After 5 minutes of roleplay, detect the subject’s emotional state once every 15 minutes.
* After 20 minutes of roleplay, detect the subject’s psychological flaws or hidden information in their mind.',
                'abilities' => 'Detect Emotional State (5m),Detect Psych Flaw (20m)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 32,
                'name' => 'Insider Information',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You have access to the best covert skills training in the world. You are highly connected among the intelligence community, during downtime you tend to learn pieces of information from the world of covert intelligence,

This can be information around new technology discoveries or procedures or can be intel rumours which may be helpful to your character.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 33,
                'name' => 'Interrogation/Investigation',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are knowledgeable in interrogation, and the psychology of investigation.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 34,
                'name' => 'Law',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are an expert in both military and civilian law. You are qualified for and experienced in representing both civilian and military personnel in court. If you spend time with alien cultures, you may be able to also take their own legal systems as a skill.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 35,
                'name' => 'Leadership',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'Once per Scene, you can take one other character under your wing.

If you are actively leading them or protecting them and you are within 50 feet with line of sight, both you and they gain +2 Body.

You may also spend a further three months adding additional people to the effects of this skill.

There is no maximum number of times you can purchase this upgrade, but no character may gain more than +2 Body gained from this skill at any one time.',
                'abilities' => 'Under Your Wing (+2 Body)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 45,
                'name' => 'Leadership Extra Person',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You can take one extra person under your wing.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 20,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 36,
                'name' => 'Medical Specialism',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'The following skills improve the range of medical knowledge, allowing characters to excel in a particular field. Learning specialties will allow you to speed up treatments when they relate to your specialist area and may allow other effects to occur.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 1,
                'specialty_type_id' => 2,
                'repeatable' => 20,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 37,
                'name' => 'Micro-Expressions',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'This Skill allows the use of Psychology or Psychotherapy skills through observation rather than direct interaction with the Subject. This includes Audio and Visual Recordings.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 38,
                'name' => 'Negotiation',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are a trained negotiator, skilled in conflict resolution without violence.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 39,
                'name' => 'Piloting',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are an accomplished pilot of Human Aircraft. If you have Astronaut, this applies to the Space Shuttle and similar Earth level spacecraft.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 40,
                'name' => 'Politics and Bureaucracy',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You understand the basics of the political system and the back-office politics that occur behind the scenes. You also understand how to navigate and manipulate the red tape of systems such as the civil service or military requisitions. If you spend time with alien cultures, you may be able to apply this skill to those cultures as well.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 41,
                'name' => 'Quantum Physics',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'The study of radioactivity & the fundamental principles of the universe. This includes Nuclear Physics.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 42,
                'name' => 'Religion and Occult',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are knowledgeable in the major Earth religions, their beliefs, and practices.

You are also knowledgeable in a variety of occult practices and their beliefs.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 43,
                'name' => 'SERE Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You have completed a military SERE (Survival, Evasion, Resistance and Escape) course, the Intelligence community equivalent, or perhaps you are simply a very skilled liar. For whatever reason, you are able to resist interrogation.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 88,
                'name' => 'Subject Matter Expert',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are particularly knowledgeable in a given area, and in managing projects relating to it.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 25,
                'name' => 'Poker Face',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMPLEX,
                'description' => 'You are particularly difficult to get a read on, psychologically speaking. This skill reduces the cost of falsifying psychological responses by one.',
                'abilities' => 'Falsify Psych Response (1V)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
        ], 'id');
    }

    public function seedBasicSkills()
    {
        $expertKnowledgeDescription = 'This increases your hand size for a chosen technology skill by one. This Skill can be repeatedly trained, granting +1 hand size each time. The maximum hand size for any skill is 12. You may **not** use this skill to increase the hand size of a Basic Skill that grants a card.';
        DB::table('skills')->upsert([
            [
                'id' => 44,
                'name' => 'Test Pilot',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You are an accomplished pilot of experimental craft.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 46,
                'name' => 'Basic Biology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of biology.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 47,
                'name' => 'Basic Botany',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of botany.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 48,
                'name' => 'Basic Chemistry',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of chemistry.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 49,
                'name' => 'Basic Computers',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of computer science. You gain the ability to use one card from Computing and one card from Communications when preparing for a skill game.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 50,
                'name' => 'Basic Engineering',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of engineering. You gain the ability to use one card from Mechanical Engineering and one card from Electrical Engineering when preparing for a skill game.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 51,
                'name' => 'Basic Explosives Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of Explosives & Demolitions. You can only use C4 & Claymores. You gain the ability to use one card from Explosives Training when preparing for a skill game.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 52,
                'name' => 'Basic Geology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have a good understanding of rocks, minerals, and landscapes.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 53,
                'name' => 'Basic Language',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt to speak/read/write one specific Language. This skill may be bought repeatedly for extra languages with each reducing the cost of Linguistics skill. The cost of the Linguistics skill may be reduced to zero months through multiple purchases of this skill.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 1,
                'specialty_type_id' => 3,
                'repeatable' => 10,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 54,
                'name' => 'Basic Larceny',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of Larceny. You gain the ability to use one card from Larceny when preparing for a skill game.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 55,
                'name' => 'Basic Mathematics',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of mathematics. You gain the ability to use one card from Cryptography when preparing for a skill game.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 56,
                'name' => 'Basic Physics',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have learnt the basic techniques of physics.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 57,
                'name' => 'Basic Psychology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You may spend 3 Vigor to falsify the response to a psychological challenge.',
                'abilities' => 'Falsify Psych Response (3V)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 60,
                'name' => 'Endurance Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have spent a long time in the gymnasium and have honed your Body. This comes with a price, and you must maintain your endurance.

You gain 1 Body Hit. You must spend 1 Month of Training or Research time every downtime maintaining this skill or it is lost. (You only begin maintaining this skill once you have it.) This Skill can be repeatedly trained, granting +1 Body each time to a max of +5. Maintaining this skill only takes 1 month regardless of level. If you have more than one level of this skill, failure to maintain loses one level per DT in which maintenance is not completed.

Additionally, at +4 Body or above, you may ignore the restrictions imposed on weapons by the Mounted Trait.',
                'abilities' => NULL,
                'upkeep' => 1,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 5,
                'body' => 1,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 61,
                'name' => 'Expert Knowledge: Computing',
                'print_name' => 'EK: Computing',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 62,
                'name' => 'Expert Knowledge: Cryptography',
                'print_name' => 'EK: Cryptography',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 63,
                'name' => 'Expert Knowledge: Electrical Engineering',
                'print_name' => 'EK: Elec. Eng.',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 64,
                'name' => 'Expert Knowledge: Explosives Training',
                'print_name' => 'EK: Demolitions',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 65,
                'name' => 'Expert Knowledge: Larceny',
                'print_name' => 'EK: Larceny',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 66,
                'name' => 'Expert Knowledge: Mechanical Engineering',
                'print_name' => 'EK: Mech. Eng.',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 67,
                'name' => 'Expert Knowledge: Paramedic',
                'print_name' => 'EK: Paramedic',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 68,
                'name' => 'Expert Knowledge: Signals Intelligence',
                'print_name' => 'EK: SIGINT',
                'skill_category_id' => SkillCategory::BASIC,
                'description' => $expertKnowledgeDescription,
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 6,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 69,
                'name' => 'Fish and Game',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You are an expert hunter. You can find food, water, and shelter while you\'re in the wilderness. You\'re also good at capturing small animals and fishing.

This Skill allows the use of ‘Negotiator’ feats on animal targets if you also have the Negotiator Skill.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 70,
                'name' => 'Heroic Vigor',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'This increases your Heroic Vigor by 1. This Skill can be repeatedly trained, granting +1 Vigor each time to a max of +5. This may be lost if you undertake actions deemed non-heroic.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 5,
                'body' => 0,
                'vigor' => 1,
                'display' => 0,
            ],
            [
                'id' => 71,
                'name' => 'Medic',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'Stabilise - with 10 Seconds of appropriate Roleplay, you may negate a Bleed effect on yourself or another player. **This ability does not cost Vigor.**',
                'abilities' => 'Stabilise Casualty (10s)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 72,
                'name' => 'Mythology',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'You have knowledge of one specific area of Religion/occult knowledge – For example, Judeo-Christian dogma, or Yoga Positions.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 1,
                'specialty_type_id' => 4,
                'repeatable' => 10,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 89,
                'name' => 'Self-Defense',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'This skill grants access to the following ability.
* Break Hold (Costs 1 Vigor): You call "Break Hold". The target must release you from a grapple, or you can resist a throw as you’ve broken their hold.',
                'abilities' => 'Break Hold (1V)',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 26,
                'name' => 'Tenured Academic',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::BASIC,
                'description' => 'Some people have spent their whole lives in academia and it shows.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 3,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
        ], 'id');
    }

    public function seedCombatSkills()
    {
        DB::table('skills')->upsert([
            [
                'id' => 73,
                'name' => 'Accuracy Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You gain access to the Lethal call for any non-melee weapon with the accurate trait capable of using it.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 12,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 74,
                'name' => 'Boxing',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You gain the following ability:
* Strike (unlimited): You may call damage with fists.
Unarmed Combat Strikes should be represented by an open palm aimed at the target’s shoulder area, but should not make actual contact.',
                'abilities' => 'Unarmed Strike',
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 75,
                'name' => 'Breacher',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'Allows the use of Future Warrior armour (+8 BP).

Allows use of a Riot Shield (+2 BP).',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 76,
                'name' => 'Gunnery Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You gain access to the Covering Fire and Spray Fire calls for any weapon capable of using them that you have access to.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 6,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 77,
                'name' => 'Heavy Weapons',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You can use all weapons in the Heavy Weapons Table. Any calls you already had access to can now be applied to Heavy Weapons capable of using them.

* You gain access to the Explosive call for any weapon capable of using it.
* You gain access to the Rend call for any weapon capable of using it.
* You gain access to the Spray Fire and Heavy Fire Calls.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 6,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 78,
                'name' => 'Martial Arts',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You gain the following abilities using your hands:
* Strike (unlimited): You cause damage as per Boxing.
* Grapple (Costs 1 Vigor) You can keep the target restrained for 10 seconds. The target may still use small ranged weapons (not Unarmed or Melee strikes) against you, provided they were holding them when the grapple began.
    A grappled target may not use any feats or abilities other than Break Hold.
    You MUST use both hands to grapple, or it ends immediately. ANY unarmed call you make after the grapple begins, (Except Knockout), ends it. If you are incapacitated or rendered unconscious the grapple automatically ends.
* Killing Blow (Costs 1 Vigor on top of the Grapple): You can, after 10 seconds of grappling an opponent, use the Killing Blow feat to make the Lethal or Sleep calls.
* Throw (Costs 1 Vigor): You call “Knockback”. The target must role-play being thrown away from you and Knocked Down.
* Break Hold (Costs 1 Vigor): You call “Break Hold”. The target must release you from a grapple, or you can resist a throw as you’ve broken their hold.
* Disarm (Costs 1 Vigor): Your opponent must drop/place what they are holding on the floor, or otherwise release it with both hands.',
                'abilities' => 'Unarmed Strike,Grapple (1V),Throw (1V),Break Hold (1V),Unarmed Disarm (1V)',
                'upkeep' => 0,
                'cost' => 12,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 79,
                'name' => 'Melee',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You gain the following ability:
* Strike (unlimited): You may call damage with melee weapons.',
                'abilities' => 'Melee Strike',
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 80,
                'name' => 'Pistols',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You can use all weapons in the Pistols Table.
* Ranged (unlimited): You may call damage with a pistol.
You gain access to the Stun call for any weapon in the Pistols table capable of using it',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 81,
                'name' => 'Point Man',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'Allows the use of Ablative Hardweave (+6 BP).

Allows the use of a Public Order Shield. (+1BP).',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 6,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 82,
                'name' => 'Primitive Weaponry',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You can use all weapons in the Primitive Weapons Table. Additionally, if you have the Armorer Skill, you may create weapons using primitive materials.

You gain access to the Bleed Call for any Primitive Weapon capable of making it.

You gain the following abilities using Primitive Weapons:
* Disarm (Costs 1 Vigor): Your opponent must drop/place their weapon on the floor, or otherwise release it with both hands.',
                'abilities' => 'Melee Disarm (1V)',
                'upkeep' => 0,
                'cost' => 9,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
            [
                'id' => 83,
                'name' => 'Tactical Training',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::COMBAT,
                'description' => 'You can use all weapons in the Personal Weapon Systems Table.

You gain access to the Bleed and Knockback calls for any weapon in the Personal Weapon Systems table capable of using them.

You gain access to the Stun and Shrapnel calls for any weapon in the Ranged Weapon Table capable of using them.',
                'abilities' => NULL,
                'upkeep' => 0,
                'cost' => 6,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 0,
                'body' => 0,
                'vigor' => 0,
                'display' => 1,
            ],
        ], 'id');
    }

    public function seedAlienSkills()
    {
    }

    public function seedSkillSpecialties()
    {
        // Archeo-anthropology
        DB::table('skill_specialties')->upsert([
            [
                'id' => 3,
                'name' => 'Celtic',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 1,
                'name' => 'Egyptian',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Far East',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 9,
                'name' => 'Germanic',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Greco-Roman',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Indo-Aryan',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Mesoamerican',
                'specialty_type_id' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Middle Eastern',
                'specialty_type_id' => 1,
            ],
        ], 'id');
        // Medical
        DB::table('skill_specialties')->upsert([
            [
                'id' => 10,
                'name' => 'Cardiothoracic',
                'specialty_type_id' => 2,
            ],
            [
                'id' => 11,
                'name' => 'Haematology',
                'specialty_type_id' => 2,
            ],
            [
                'id' => 12,
                'name' => 'Infectious Diseases',
                'specialty_type_id' => 2,
            ],
            [
                'id' => 15,
                'name' => 'Neurosurgery',
                'specialty_type_id' => 2,
            ],
            [
                'id' => 13,
                'name' => 'Oncology',
                'specialty_type_id' => 2,
            ],
            [
                'id' => 14,
                'name' => 'Plastic Surgery',
                'specialty_type_id' => 2,
            ],
        ], 'id');
        // Mythology
        DB::table('skill_specialties')->upsert([
            [
                'id' => 38,
                'name' => 'Aztec/Mayan',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 40,
                'name' => 'Buddhism',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 39,
                'name' => 'Hinduism',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 35,
                'name' => 'Judeo-Christian',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 37,
                'name' => 'Native American',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 36,
                'name' => 'Norse',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 41,
                'name' => 'Japanese',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 42,
                'name' => 'Chinese',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 43,
                'name' => 'Indo-Aryan',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 44,
                'name' => 'Māori',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 45,
                'name' => 'Eastern European',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 46,
                'name' => 'Greco-Roman',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 47,
                'name' => 'Egyptian',
                'specialty_type_id' => 4,
            ],
            [
                'id' => 48,
                'name' => 'Celtic',
                'specialty_type_id' => 4,
            ],
        ], 'id');
        $this->seedLinguistics();
    }

    protected function seedLinguistics()
    {
        // Linguistics
        DB::table('skill_specialties')->upsert([
            [
                'name' => 'Afrikaans',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Albanian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'American Sign Language',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Arabic',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Armenian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Aymara',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Bengali',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Berber',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'British Sign Language',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Burmese',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Catalan',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Cherokee',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Chinese (Cantonese)',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Chinese (Mandarin)',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Chinese (Yue Chinese)',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Chinese (Wu Chinese)',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Danish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Dutch',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Egyptian Hieroglyphs',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Filipino',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Finnish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'French',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Futhark',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Gaelic',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'German',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Greek',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Greek (Ancient)',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Guarani',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Hebrew',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Hindi',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Hungarian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Irish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Italian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Japanese',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Korean',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Kurdish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Latin',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Latvian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Lithuanian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Macedonian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Malay',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Marathi',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Mongolian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Norwegian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Ogham',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Pashto',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Persian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Polish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Portuguese',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Punjabi',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Quechua',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Romanian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Russian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Serb-Croatian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Slovak',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Somali',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Sotho',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Spanish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Swahili',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Swedish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Tamil',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Telugu',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Tswana',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Turkish',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Ukrainian',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Urdu',
                'specialty_type_id' => 3,
            ],
            [
                'name' => 'Vietnamese',
                'specialty_type_id' => 3,
            ],
        ], 'name');
    }

    public function seedSkillPrereqs()
    {
        DB::table('skill_prereqs')->truncate();
        DB::table('skill_prereqs')->upsert([
            [
                'skill_id' => 16,
                'prereq_id' => 7,
                'always_required' => 1,
            ],
            [
                'skill_id' => 31,
                'prereq_id' => 19,
                'always_required' => 0,
            ],
            [
                'skill_id' => 31,
                'prereq_id' => 20,
                'always_required' => 0,
            ],
            [
                'skill_id' => 36,
                'prereq_id' => 7,
                'always_required' => 1,
            ],
            [
                'skill_id' => 37,
                'prereq_id' => 19,
                'always_required' => 0,
            ],
            [
                'skill_id' => 37,
                'prereq_id' => 20,
                'always_required' => 0,
            ],
            [
                'skill_id' => 41,
                'prereq_id' => 15,
                'always_required' => 1,
            ],
            [
                'skill_id' => 44,
                'prereq_id' => 39,
                'always_required' => 1,
            ],
            [
                'skill_id' => 45,
                'prereq_id' => 35,
                'always_required' => 1,
            ],
            [
                'skill_id' => 73,
                'prereq_id' => 83,
                'always_required' => 0,
            ],
            [
                'skill_id' => 73,
                'prereq_id' => 82,
                'always_required' => 0,
            ],
            [
                'skill_id' => 75,
                'prereq_id' => 81,
                'always_required' => 1,
            ],
            [
                'skill_id' => 77,
                'prereq_id' => 83,
                'always_required' => 1,
            ],
            [
                'skill_id' => 82,
                'prereq_id' => 79,
                'always_required' => 1,
            ],
            [
                'skill_id' => 83,
                'prereq_id' => 80,
                'always_required' => 1,
            ],
            [
                'skill_id' => 86,
                'prereq_id' => 16,
                'always_required' => 1,
            ],
            [
                'skill_id' => 87,
                'prereq_id' => 18,
                'always_required' => 1,
            ],
            [
                'skill_id' => 61,
                'prereq_id' => 1,
                'always_required' => 1,
            ],
            [
                'skill_id' => 62,
                'prereq_id' => 2,
                'always_required' => 1,
            ],
            [
                'skill_id' => 63,
                'prereq_id' => 3,
                'always_required' => 1,
            ],
            [
                'skill_id' => 64,
                'prereq_id' => 4,
                'always_required' => 1,
            ],
            [
                'skill_id' => 65,
                'prereq_id' => 5,
                'always_required' => 1,
            ],
            [
                'skill_id' => 66,
                'prereq_id' => 6,
                'always_required' => 1,
            ],
            [
                'skill_id' => 67,
                'prereq_id' => 7,
                'always_required' => 1,
            ],
            [
                'skill_id' => 68,
                'prereq_id' => 8,
                'always_required' => 1,
            ],
            [
                'skill_id' => 10,
                'prereq_id' => 9,
                'always_required' => 1,
            ],
            [
                'skill_id' => 92,
                'prereq_id' => 91,
                'always_required' => 1,
            ],
            [
                'skill_id' => 25,
                'prereq_id' => 19,
                'always_required' => 0,
            ],
            [
                'skill_id' => 25,
                'prereq_id' => 57,
                'always_required' => 0,
            ],
            [
                'skill_id' => 26,
                'prereq_id' => 28,
                'always_required' => 1,
            ],
        ], ['skill_id', 'prereq_id']);
    }

    public function seedSkillLockouts()
    {
        DB::table('skill_lockouts')->truncate();
        DB::table('skill_lockouts')->upsert([
            [
                'skill_id' => 16,
                'lockout_id' => 18,
            ],
            [
                'skill_id' => 16,
                'lockout_id' => 87,
            ],
            [
                'skill_id' => 18,
                'lockout_id' => 16,
            ],
            [
                'skill_id' => 18,
                'lockout_id' => 86,
            ],
            [
                'skill_id' => 14,
                'lockout_id' => 53,
            ],
            [
                'skill_id' => 19,
                'lockout_id' => 57,
            ],
            [
                'skill_id' => 5,
                'lockout_id' => 54,
            ],
            [
                'skill_id' => 4,
                'lockout_id' => 51,
            ],
            [
                'skill_id' => 12,
                'lockout_id' => 47,
            ],
            [
                'skill_id' => 15,
                'lockout_id' => 48,
            ],
            [
                'skill_id' => 42,
                'lockout_id' => 72,
            ],
            [
                'skill_id' => 78,
                'lockout_id' => 89,
            ],
        ], ['skill_id', 'lockout_id']);
    }

    public function seedSkillDiscounts()
    {
        DB::table('skill_discounts')->truncate();
        DB::table('skill_discounts')->upsert([
            [
                'discounting_skill' => 46,
                'discounted_skill' => 13,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 46,
                'discounted_skill' => 17,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 46,
                'discounted_skill' => 18,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 47,
                'discounted_skill' => 12,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 48,
                'discounted_skill' => 15,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 49,
                'discounted_skill' => 1,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 49,
                'discounted_skill' => 8,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 50,
                'discounted_skill' => 3,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 50,
                'discounted_skill' => 6,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 51,
                'discounted_skill' => 4,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 52,
                'discounted_skill' => 9,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 53,
                'discounted_skill' => 14,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 54,
                'discounted_skill' => 5,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 55,
                'discounted_skill' => 2,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 55,
                'discounted_skill' => 21,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 56,
                'discounted_skill' => 15,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 57,
                'discounted_skill' => 19,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 72,
                'discounted_skill' => 42,
                'discount' => 3,
            ],
            [
                'discounting_skill' => 89,
                'discounted_skill' => 78,
                'discount' => 3,
            ],
        ], ['discounting_skill', 'discounted_skill']);
    }

    public function seedCardSkills()
    {
        DB::table('card_type_skill')->truncate();
        DB::table('card_type_skill')->upsert([
            [
                'skill_id' => 1,
                'card_type_id' => 1,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 2,
                'card_type_id' => 2,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 3,
                'card_type_id' => 3,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 4,
                'card_type_id' => 4,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 5,
                'card_type_id' => 5,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 6,
                'card_type_id' => 6,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 7,
                'card_type_id' => 7,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 8,
                'card_type_id' => 8,
                'number' => 6,
                'total' => 1,
            ],
            [
                'skill_id' => 49,
                'card_type_id' => 1,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 49,
                'card_type_id' => 8,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 50,
                'card_type_id' => 3,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 50,
                'card_type_id' => 6,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 51,
                'card_type_id' => 4,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 54,
                'card_type_id' => 5,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 55,
                'card_type_id' => 2,
                'number' => 1,
                'total' => 0,
            ],
            [
                'skill_id' => 61,
                'card_type_id' => 1,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 62,
                'card_type_id' => 2,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 63,
                'card_type_id' => 3,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 64,
                'card_type_id' => 4,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 65,
                'card_type_id' => 5,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 66,
                'card_type_id' => 6,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 67,
                'card_type_id' => 7,
                'number' => 1,
                'total' => 1,
            ],
            [
                'skill_id' => 68,
                'card_type_id' => 8,
                'number' => 1,
                'total' => 1,
            ],
        ], 'skill_id');
    }

    public function seedBackgroundSkills()
    {
        DB::table('background_skill')->truncate();
        DB::table('background_skill')->insertOrIgnore([
            [
                'background_id' => 1,
                'skill_id' => 71,
            ],
            [
                'background_id' => 1,
                'skill_id' => 74,
            ],
            [
                'background_id' => 1,
                'skill_id' => 79,
            ],
            [
                'background_id' => 1,
                'skill_id' => 80,
            ],
            [
                'background_id' => 1,
                'skill_id' => 83,
            ],
            [
                'background_id' => 2,
                'skill_id' => 32,
            ],
            [
                'background_id' => 2,
                'skill_id' => 71,
            ],
            [
                'background_id' => 2,
                'skill_id' => 74,
            ],
            [
                'background_id' => 2,
                'skill_id' => 79,
            ],
            [
                'background_id' => 2,
                'skill_id' => 80,
            ],
            [
                'background_id' => 3,
                'skill_id' => 28,
            ],
            [
                'background_id' => 3,
                'skill_id' => 71,
            ],
            [
                'background_id' => 3,
                'skill_id' => 74,
            ],
            [
                'background_id' => 3,
                'skill_id' => 79,
            ],
            [
                'background_id' => 3,
                'skill_id' => 80,
            ],
        ]);
    }

    public function seedSkillTraining()
    {
        DB::table('skill_training')->truncate();
        DB::table('skill_training')->upsert([
            [
                'taught_skill_id' => 1,
                'trained_skill_id' => 49,
            ],
            [
                'taught_skill_id' => 2,
                'trained_skill_id' => 55,
            ],
            [
                'taught_skill_id' => 3,
                'trained_skill_id' => 50,
            ],
            [
                'taught_skill_id' => 4,
                'trained_skill_id' => 51,
            ],
            [
                'taught_skill_id' => 5,
                'trained_skill_id' => 54,
            ],
            [
                'taught_skill_id' => 6,
                'trained_skill_id' => 50,
            ],
            [
                'taught_skill_id' => 8,
                'trained_skill_id' => 49,
            ],
            [
                'taught_skill_id' => 9,
                'trained_skill_id' => 52,
            ],
            [
                'taught_skill_id' => 12,
                'trained_skill_id' => 47,
            ],
            [
                'taught_skill_id' => 13,
                'trained_skill_id' => 46,
            ],
            [
                'taught_skill_id' => 14,
                'trained_skill_id' => 53,
            ],
            [
                'taught_skill_id' => 15,
                'trained_skill_id' => 48,
            ],
            [
                'taught_skill_id' => 15,
                'trained_skill_id' => 56,
            ],
            [
                'taught_skill_id' => 17,
                'trained_skill_id' => 46,
            ],
            [
                'taught_skill_id' => 18,
                'trained_skill_id' => 46,
            ],
            [
                'taught_skill_id' => 19,
                'trained_skill_id' => 57,
            ],
            [
                'taught_skill_id' => 21,
                'trained_skill_id' => 55,
            ],
            [
                'taught_skill_id' => 42,
                'trained_skill_id' => 72,
            ],
            [
                'taught_skill_id' => 78,
                'trained_skill_id' => 89,
            ],
        ], [
            'taught_skill_id',
            'trained_skill_id',
        ]);
    }

    public function seedSystemSkills()
    {
        DB::table('skills')->upsert([
            [
                'id' => Skill::PLOT_CHANGE,
                'name' => 'Plot Changes',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SYSTEM,
                'description' => 'See related character log for details.',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 255,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
            [
                'id' => 91,
                'name' => 'Resuscitation',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SYSTEM,
                'description' => 'The consequence of being brought back to life.',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 255,
                'body' => 0,
                'vigor' => -1,
                'display' => 0,
            ],
            [
                'id' => 92,
                'name' => 'Resuscitation Vigor Buyback',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SYSTEM,
                'description' => 'Getting your lost Vigor back.',
                'upkeep' => 0,
                'cost' => 3,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 255,
                'body' => 0,
                'vigor' => 1,
                'display' => 0,
            ],
            [
                'id' => Skill::SYSTEM_CHANGE,
                'name' => 'System Changes',
                'print_name' => NULL,
                'skill_category_id' => SkillCategory::SYSTEM,
                'description' => 'See related character log for details.',
                'upkeep' => 0,
                'cost' => 0,
                'specialties' => 0,
                'specialty_type_id' => NULL,
                'repeatable' => 255,
                'body' => 0,
                'vigor' => 0,
                'display' => 0,
            ],
        ], 'id');
    }
}
