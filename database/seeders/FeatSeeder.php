<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedFeats();
        $this->seedBackgroundFeats();
        $this->seedSkillFeats();
    }

    public function seedFeats()
    {
        $toolkitDescription = '**For a cost of 1 Vigor**, you can use this feat to automatically solve one problem card from a skill game that you are attempting of the appropriate type (with a time of zero). You must state that you are using this feat before the problem solving has begun and indicate which card it is to be used on before any are revealed – and only one card can be negated per problem, regardless of the number of participants.

This feat can, **in addition to the above**, be used to select additional cards from your deck to attempt to solve a problem. **For a cost of 2 Vigor per card**, you may draw a card (selected at random), from those in your deck that are not in your hand. You can declare this at any time during the problem card game. This may be used by a Technical Mentor joining the game. **You may draw additional cards equal to your current hand size.** You ***must*** be able to physrep the additional cards you have drawn.';

        DB::table('feats')->upsert([
            [
                'id' => 1,
                'name' => 'Dodge!',
                'description' => 'This enables the character to dodge out of harm\'s way and take less damage from a blow. This feat negates one full combat call, and you and anything you are carrying take no damage or effects.

**The exception to this is the Lethal call – used in response to a call of Lethal, this feat immediately restores you to 1 Body.**',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 2,
                'name' => 'Die Hard',
                'description' => 'Providing you are above zero Body, you refresh Body to your current maximum. This feat has a 10 minute \'cooldown\' between uses.

This feat cannot be used whilst you are inside the 10 minute cooldown period of Get Back in The Fight.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 3,
                'name' => 'Flash of Insight',
                'description' => 'The player may request a hint as to how to approach or solve a particular problem. This may be used as a form of \'Spider Sense\' (ref\'s discretion). You may be asked if you want to use this feat by a ref in certain situations.

This feat costs 2 Vigor unless you have a skill that is relevant to the problem. Skills which grant a discount on this feat are listed in the Skill Description. This discount may also be granted at the discretion of the Event Referee.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '2 or 1',
            ],
            [
                'id' => 4,
                'name' => 'Total Focus',
                'description' => 'This can be used for a character to continue a non-combat task after time-out. They can receive their results at time-in the following morning.

In the case of a 24-hour event this may be used for a player to go out of character to sleep, while their character continues their non-combat task.

This feat costs zero Vigor to use, however it prevents your Vigor track from refreshing back up to full overnight.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '0',
            ],
            [
                'id' => 5,
                'name' => 'Last Heroic Act',
                'description' => 'This feat allows you to carry out a final heroic action and lasts for up to 5 minutes, or until the action is completed (if shorter). The feat can only be used for "heroic" actions. These should be selfless in nature such as protecting others at the cost of your own life. Selfish actions such as carrying out revenge may not benefit from this feat.

Whilst under the effects of this feat you may ignore all damage and all restraining calls and ignore the effects on any previously opened wound cards. You also immediately gain all combat skills. Once the feat has run its duration you become Terminal, and this cannot be changed by any means. Your character either dies, or is for some appropriate reason no longer able to serve with the SEF and must be retired.

**THIS FEAT MAY BE USED EVEN WHEN YOU HAVE ZERO VIGOR.**',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '0',
            ],
            [
                'id' => 6,
                'name' => 'All Guns Blazing',
                'description' => 'For five minutes after using this feat, the player is not required to roleplay changing their magazine.

Only weapons with the All Guns Blazing trait can be used with this feat.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 7,
                'name' => 'A Very Distinctive...',
                'description' => 'You may use this feat to gain an insight into the motivations and allegiance of a group or an individual you are observing. For this feat you should use the call "Very Distinctive X" where X can be stance/boots/sound/watch/tattoo etc.

E.g. "That guy works for the Yakuza, you can tell by their Very Distinctive tattoos".

You may also use this feat to determine the Maximum Vigor of someone you have observed for at least five minutes, however some Legendary NPCs may simply get the equivalent of a skull icon.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 8,
                'name' => 'Bodyguard',
                'description' => 'You can nominate an attack that would normally hit another person or object (within 10 feet of you) to hit you instead. You cannot avoid this damage by any means.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 9,
                'name' => 'Botch Job',
                'description' => 'Within reason (ref\'s discretion), you improvise with what you have and immediately solve a skill game card problem.

The device will only work for a short time (event ref\'s discretion). Future attempts to fix the problem will have +1 problem card added to the skill game unless it is a Demolitions problem. If this feat is used on a Demolition Skill Game, the device detonates after five minutes.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 10,
                'name' => 'Cat-like Reflexes',
                'description' => 'You can ignore calls of Knockback, Mass Knockback, and Global Knockback for sixty seconds. Call "Resist" to any subsequent calls during this time.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 11,
                'name' => 'Codebreaker',
                'description' => 'You may spend one point of Vigor to decode a specific word in a Ciphers problem, without attempting the attendant Skill Card Game.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 12,
                'name' => 'Cauterize',
                'description' => 'AKA "Don’t Have Time To Bleed."

This Feat negates a Bleed effect on yourself or another character, provided they are above zero Body.

Using this ability on a **Critical** character does NOT negate the effect. Instead it pauses their bleed count.

If you leave a **Critical** character, or the **Critical** Character moves they will begin bleeding again.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 13,
                'name' => 'Drug Resistance',
                'description' => 'The character can resist the effects of a drug that has entered into their system for up to five minutes per point of Vigor spent.

This must be role-played, as the effects of the drug are slowly creeping up on the character.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 14,
                'name' => 'Escape Artist',
                'description' => 'You are an expert at twisting and turning your way out of bonds. You may use this skill to escape from being tied up. At referee\'s discretion this may allow you to help with movement in a confined space.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 15,
                'name' => 'Firm Grip',
                'description' => 'This feat negates a call of "Disarm" called against your character.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 16,
                'name' => 'Fly It Like You Stole It',
                'description' => 'After five minutes of familiarising yourself with a craft for which you do not possess the relevant Alien Technology Skill, you may spend Vigor to fly the craft as normal.

Basic Alien Craft cost 2 Vigor to operate. More Complex Craft may have an increased Vigor Cost, or increased familiarisation time at the Event Referee\'s discretion.

You may spend additional points of Vigor to reduce the time needed to use this ability, at a rate of 1 Vigor per five minutes, to a minimum of 30 seconds.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '2+',
            ],
            [
                'id' => 17,
                'name' => 'Get Back In The Fight',
                'description' => 'Providing they are above Zero Body, a target character you select regains Body to their current maximum. This feat may not be used on yourself.

This feat breaks Stun and Paralysis.

This feat has a 10 minute cooldown between uses on the same target. This feat has no effect on someone inside their cooldown period from using Die Hard, and your Vigor is refunded in such an instance.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 18,
                'name' => 'Hunker Down',
                'description' => 'AKA "Got Time to Duck?"

If you are carrying an appropriately modern shield physrep, you may stand still and call this feat. Until you move again, your shield counts as standard cover for you and one other person within five feet.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 19,
                'name' => 'Explosives Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 20,
                'name' => 'Electrical Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 21,
                'name' => 'Computing Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 22,
                'name' => 'Communications Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 23,
                'name' => 'Mechanical Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 24,
                'name' => 'Larceny Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 25,
                'name' => 'Medical Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 26,
                'name' => 'Interrogator',
                'description' => 'After five minutes of roleplay with someone, or observing someone else doing so, this feat compels a target over which you hold a position of authority to truthfully answer questions put to them.

Some (non-exhaustive) examples of positions of authority:
- A criminal has been captured and placed under arrest.
- A Senior Officer is asking questions about a previous mission.
- A lawyer is cross-examining a witness.

Using this feat costs 2 Vigor.

NOTE: Holding your gun to someone\'s head is a threat, not a position of authority. While an interrogation scene may involve enhanced techniques with the OC consent of all involved, this is not the primary or only way to make use of this feat.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '2',
            ],
            [
                'id' => 27,
                'name' => 'Killing Blow',
                'description' => 'This feat allows you to make either the Lethal or Sleep call against a Stunned, Unconscious, restrained. or unresisting target.

This can be called with any weapon, including unarmed strikes. This is a close quarter skill and requires you to be within five feet of the target in order to use this call.

Note: For monstrous/non-humanoid creatures, a visible weak spot must be present for them to be a valid target.

This feat **cannot** be given to an NPC without approval from the System Referees.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 28,
                'name' => 'Emergency Measures',
                'description' => 'Once per Event, you may choose to complete 1 Surgical Procedure caused by a wound card in 60 seconds. This may be used at any time, including when a surgery would otherwise fail. Mechanically, this counts as solving all Problem Cards and Complications presented by the Procedure. This must be roleplayed by performing some sort of dramatic and non-standard medical solution.

Use of this Feat counts as an additional Surgical Procedure for the purposes of the patient’s treatment track.',
                'per_event' => 1,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 29,
                'name' => 'Marksman',
                'description' => 'This feat allows you to make the Lethal call when using ranged weapons with the Accurate trait.
Before making a call of Lethal, you must spend 15 seconds bracing and aiming a ranged weapon. During this period you cannot make any other calls, use any other feats, or move faster than a slow walking pace. Taking any other actions resets your count. Taking damage, however, does not reset your count.

Certain weapons will reduce this time to 10 or even 5 seconds - See the Personal Weapon Systems and Heavy Weapons tables for details.

This feat **cannot** be given to an NPC without approval from the System Referees.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 30,
                'name' => 'Natural Resistance',
                'description' => 'The character can resist the effects of any one natural toxin or disease.

Note: Although you are resistant to the toxin or disease you still get some symptoms and should roleplay a reduced effect. (Even if you won\'t die or take damage from the source).

This Feat costs 1 Vigor to use, which remains \'spent\' and does not refresh until you are cured of the disease. Some diseases that can be found on other planets may not be resisted. If this is the case, the Event Referee will inform you when you spend the feat, and your Vigor is refunded.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 31,
                'name' => 'Negotiator',
                'description' => 'Providing there has been no combat in the last 10 seconds, a character may force a target to listen to them. This target must listen and make no offensive moves against the character or their allies.

The target does not need to be swayed by the character.

The effect will end after 5 minutes OR if a referee believes the negotiations have broken down or failed OR if the target is attacked.

You may spend multiple points of Vigor to affect multiple targets in a group, but if the effect ends on any target it ends for all affected.

This feat may be used by proxy through another character translating at a cost of +1 total Vigor.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1+',
            ],
            [
                'id' => 32,
                'name' => 'Numb3rs',
                'description' => 'This feat allows you to use mathematical modelling to assist in solving problems. You may spend this feat when working on or assisting on any Technical Skill Game to reduce the time on the solution cards by 10%. You may use this feat even on Skill Games you do not have the relevant Technology Skill for, and do not count towards the two player per problem limit **unless** you play a solution card.

You may only spend this feat once per problem, but multiple players can each spend it for a stacking discount up to 50%.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 33,
                'name' => 'Old College Professor',
                'description' => 'You may act as though you have a non-Alien, non-Combat skill you don\'t have for the solution of one particular problem. This does not confer any cards, feats, or special abilities. You need to roleplay where the knowledge of this skill has come from.

*This feat is limited to one use per day, unless you possess the Tenured Academic skill.*',
                'per_event' => 1,
                'per_day' => 0,
                'print_name' => 'Old College Prof.',
                'cost' => '',
            ],
            [
                'id' => 34,
                'name' => 'On Your Feet Soldier',
                'description' => 'Using your skill and appropriate PhysReps, (bandages or dressings etc.), you can cause an otherwise **WOUNDED** character (a character who is at Zero Body) to be able to run with your assistance.

This feat ends when you are no longer in physical contact with the player OR up to a maximum of five minutes, whichever is shorter.

During this time, the player is no longer Bleeding, but cannot use Skills or any weapons other than Pistols.

This feat breaks Stun and Paralysis.

Once the effect of this feat ends, if the character has not been stabilised, they begin bleeding again.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 35,
                'name' => 'Polyglot',
                'description' => 'You can pick up languages extremely quickly. You may identify the base roots of an Alien language, and spend 1 Vigor per hour to speak, read, or write it. After a period of time interacting with an Alien Culture this cost may be waived by the Event Referee at their discretion.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 36,
                'name' => 'Technical Mentor',
                'description' => 'You, or another player you designate, can now play a card on a problem which is already being worked on by two people.

Only one additional card may be played on a problem, and once a Mentor card has been played on a problem, no-one else can use Technical Mentor on that problem.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 37,
                'name' => 'Tick Tock Motherfucker',
                'description' => 'Use of this feat reduces the time required to deploy a pre-made explosive with the bomb trait (claymore, IED etc) to 30 seconds.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 38,
                'name' => 'Tomb Raider',
                'description' => 'You can study an ancient artefact outside of your specialty area and after a period of time (ref\'s discretion) you can identify pertinent and relevant information.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 39,
                'name' => 'Torture Resistance',
                'description' => 'This feat allows you to:
- Counter the feats \'Interrogator\' and \'Negotiator\'.
- If you have been affected by mind control, you may resist and break out of the mind control for 5 minutes.
- Using a Torture Resistance reduces Stun and Paralyse time from 30 and 10 seconds to 0 seconds.

Note: This does not work on Psychology Challenges.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 40,
                'name' => 'Tracker',
                'description' => 'You can track by asking the referee for details about local tracks and which direction they lead. Following tracks must be done slowly. You can find out the number of individuals that have gone past, the type, (bipedal, quadruped), and approximate size. You can also get a sense of the speed of movement.',
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 41,
                'name' => 'We Have A Job To Do',
                'description' => 'Through calls of encouragement/a speech the user inspires all friendly personnel who can hear them. This grants the listeners 10 additional Vigor, up to their maximum.

This feat can only be used once per event, per level in the Leadership skill.

**This feat does not affect the character who made the call.**',
                'per_event' => 1,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '',
            ],
            [
                'id' => 42,
                'name' => 'Cryptographic Toolkit',
                'description' => $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
            [
                'id' => 43,
                'name' => 'Basic Skill Toolkit',
                'description' => 'This feat applies to Basic skills that provide cards.

' . $toolkitDescription,
                'per_event' => 0,
                'per_day' => 0,
                'print_name' => NULL,
                'cost' => '1 or 2',
            ],
        ], 'id', [
            'name',
            'print_name',
            'description',
            'per_event',
            'per_day',
            'cost',
        ]);
    }

    public function seedBackgroundFeats()
    {
        DB::table('background_feat')->truncate();
        DB::table('background_feat')->insert([
            [
                'background_id' => 1,
                'feat_id' => 1
            ],
            [
                'background_id' => 1,
                'feat_id' => 2
            ],
            [
                'background_id' => 1,
                'feat_id' => 3
            ],
            [
                'background_id' => 1,
                'feat_id' => 4
            ],
            [
                'background_id' => 1,
                'feat_id' => 5
            ],
            [
                'background_id' => 2,
                'feat_id' => 1
            ],
            [
                'background_id' => 2,
                'feat_id' => 2
            ],
            [
                'background_id' => 2,
                'feat_id' => 3
            ],
            [
                'background_id' => 2,
                'feat_id' => 4
            ],
            [
                'background_id' => 2,
                'feat_id' => 5
            ],
            [
                'background_id' => 3,
                'feat_id' => 1
            ],
            [
                'background_id' => 3,
                'feat_id' => 2
            ],
            [
                'background_id' => 3,
                'feat_id' => 3
            ],
            [
                'background_id' => 3,
                'feat_id' => 4
            ],
            [
                'background_id' => 3,
                'feat_id' => 5
            ],
        ]);
    }

    public function seedSkillFeats()
    {
        DB::table('feat_skill')->truncate();
        DB::table('feat_skill')->insert([
            [
                'skill_id' => 1,
                'feat_id' => 9
            ],
            [
                'skill_id' => 1,
                'feat_id'=> 21
            ],
            [
                'skill_id' => 2,
                'feat_id' => 11
            ],
            [
                'skill_id' => 2,
                'feat_id' => 42
            ],
            [
                'skill_id' => 3,
                'feat_id' => 9
            ],
            [
                'skill_id' => 3,
                'feat_id' => 20
            ],
            [
                'skill_id' => 4,
                'feat_id' => 9
            ],
            [
                'skill_id' => 4,
                'feat_id' => 19
            ],
            [
                'skill_id' => 5,
                'feat_id' => 14
            ],
            [
                'skill_id' => 5,
                'feat_id' => 24
            ],
            [
                'skill_id' => 6,
                'feat_id' => 9
            ],
            [
                'skill_id' => 6,
                'feat_id' => 23
            ],
            [
                'skill_id' => 7,
                'feat_id' => 17
            ],
            [
                'skill_id' => 7,
                'feat_id' => 25
            ],
            [
                'skill_id' => 8,
                'feat_id' => 9
            ],
            [
                'skill_id' => 8,
                'feat_id' => 22
            ],
            [
                'skill_id' => 9,
                'feat_id' => 38
            ],
            [
                'skill_id' => 11,
                'feat_id' => 3
            ],
            [
                'skill_id' => 12,
                'feat_id' => 13
            ],
            [
                'skill_id' => 12,
                'feat_id' => 30
            ],
            [
                'skill_id' => 14,
                'feat_id' => 35
            ],
            [
                'skill_id' => 15,
                'feat_id' => 37
            ],
            [
                'skill_id' => 16,
                'feat_id' => 28
            ],
            [
                'skill_id' => 21,
                'feat_id' => 32
            ],
            [
                'skill_id' => 22,
                'feat_id' => 17
            ],
            [
                'skill_id' => 22,
                'feat_id' => 37
            ],
            [
                'skill_id' => 24,
                'feat_id' => 10
            ],
            [
                'skill_id' => 24,
                'feat_id' => 34
            ],
            [
                'skill_id' => 26,
                'feat_id' => 33
            ],
            [
                'skill_id' => 27,
                'feat_id' => 8
            ],
            [
                'skill_id' => 27,
                'feat_id' => 15
            ],
            [
                'skill_id' => 28,
                'feat_id' => 33
            ],
            [
                'skill_id' => 29,
                'feat_id' => 40
            ],
            [
                'skill_id' => 30,
                'feat_id' => 3
            ],
            [
                'skill_id' => 32,
                'feat_id' => 7
            ],
            [
                'skill_id' => 33,
                'feat_id' => 26
            ],
            [
                'skill_id' => 34,
                'feat_id' => 3
            ],
            [
                'skill_id' => 35,
                'feat_id' => 41
            ],
            [
                'skill_id' => 36,
                'feat_id' => 28
            ],
            [
                'skill_id' => 38,
                'feat_id' => 31
            ],
            [
                'skill_id' => 40,
                'feat_id' => 3
            ],
            [
                'skill_id' => 41,
                'feat_id' => 3
            ],
            [
                'skill_id' => 42,
                'feat_id' => 3
            ],
            [
                'skill_id' => 43,
                'feat_id' => 13
            ],
            [
                'skill_id' => 43,
                'feat_id' => 39
            ],
            [
                'skill_id' => 44,
                'feat_id' => 16
            ],
            [
                'skill_id' => 45,
                'feat_id' => 41
            ],
            [
                'skill_id' => 60,
                'feat_id' => 30
            ],
            [
                'skill_id' => 69,
                'feat_id' => 40
            ],
            [
                'skill_id' => 71,
                'feat_id' => 12
            ],
            [
                'skill_id' => 73,
                'feat_id' => 27
            ],
            [
                'skill_id' => 73,
                'feat_id' => 29
            ],
            [
                'skill_id' => 75,
                'feat_id' => 18
            ],
            [
                'skill_id' => 76,
                'feat_id' => 6
            ],
            [
                'skill_id' => 78,
                'feat_id' => 10
            ],
            [
                'skill_id' => 78,
                'feat_id' => 27
            ],
            [
                'skill_id' => 81,
                'feat_id' => 10
            ],
            [
                'skill_id' => 82,
                'feat_id' => 15
            ],
            [
                'skill_id' => 82,
                'feat_id' => 27
            ],
            [
                'skill_id' => 87,
                'feat_id' => 28
            ],
            [
                'skill_id' => 88,
                'feat_id' => 36
            ],
            [
                'skill_id' => 88,
                'feat_id' => 43
            ],
        ]);
    }
}
