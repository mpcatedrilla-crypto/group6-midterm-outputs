<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default user for testing
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        // Create sample events
        $events = [
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Annual technology conference featuring AI and cloud computing',
                'event_date' => '2024-11-15',
            ],
            [
                'Project Kickoff Meeting',
                'description' => 'Initial planning session for the new product launch',
                'event_date' => '2024-10-20',
            ],
            [
                'title' => 'Team Building Day',
                'description' => 'Outdoor activities and team bonding exercises',
                'event_date' => '2024-12-05',
            ],
            [
                'title' => 'Client Presentation',
                'description' => 'Quarterly review with key stakeholders',
                'event_date' => '2024-11-01',
            ],
            [
                'title' => 'Year-End Gala',
                'description' => 'Celebration of annual achievements',
                'event_date' => '2024-12-20',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        // Create sample participants
        $participants = [
            ['name' => 'John Smith', 'email' => 'john.smith@email.com', 'event_id' => 1],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.j@email.com', 'event_id' => 1],
            ['name' => 'Michael Chen', 'email' => 'm.chen@email.com', 'event_id' => 2],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@email.com', 'event_id' => 2],
            ['name' => 'Robert Wilson', 'email' => 'rob.wilson@email.com', 'event_id' => 3],
            ['name' => 'Lisa Anderson', 'email' => 'lisa.a@email.com', 'event_id' => 1],
            ['name' => 'David Brown', 'email' => 'd.brown@email.com', 'event_id' => 4],
            ['name' => 'Jennifer Lee', 'email' => 'jen.lee@email.com', 'event_id' => 4],
            ['name' => 'James Taylor', 'email' => 'james.t@email.com', 'event_id' => 5],
            ['name' => 'Maria Garcia', 'email' => 'maria.g@email.com', 'event_id' => 3],
        ];

        foreach ($participants as $participantData) {
            Participant::create($participantData);
        }
    }
}
