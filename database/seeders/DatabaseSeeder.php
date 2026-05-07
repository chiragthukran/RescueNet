<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Calamity;
use App\Models\LocationLog;
use App\Models\Message;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Create 5 agency users ---
        $agencies = [
            [
                'name' => 'National Disaster Response Force',
                'email' => 'ndrf@rescuenet.test',
                'password' => Hash::make('password'),
                'phone' => '+91-9876543210',
                'organization' => 'NDRF India',
                'agency_type' => 'government',
                'description' => 'India\'s primary disaster response agency for natural and man-made calamities.',
                'latitude' => 28.6139,
                'longitude' => 77.2090,
                'status' => 'active',
            ],
            [
                'name' => 'Red Cross Emergency Unit',
                'email' => 'redcross@rescuenet.test',
                'password' => Hash::make('password'),
                'phone' => '+91-9876543211',
                'organization' => 'Indian Red Cross Society',
                'agency_type' => 'ngo',
                'description' => 'Humanitarian organization providing emergency assistance and disaster relief.',
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'status' => 'active',
            ],
            [
                'name' => 'State Fire Services',
                'email' => 'fire@rescuenet.test',
                'password' => Hash::make('password'),
                'phone' => '+91-9876543212',
                'organization' => 'Maharashtra Fire Brigade',
                'agency_type' => 'fire_rescue',
                'description' => 'State-level fire and rescue operations for Maharashtra.',
                'latitude' => 18.5204,
                'longitude' => 73.8567,
                'status' => 'active',
            ],
            [
                'name' => 'Medical Emergency Team',
                'email' => 'medical@rescuenet.test',
                'password' => Hash::make('password'),
                'phone' => '+91-9876543213',
                'organization' => 'AIIMS Emergency Response',
                'agency_type' => 'medical',
                'description' => 'Rapid medical deployment team for disaster zones.',
                'latitude' => 12.9716,
                'longitude' => 77.5946,
                'status' => 'active',
            ],
            [
                'name' => 'Coast Guard Rescue',
                'email' => 'coastguard@rescuenet.test',
                'password' => Hash::make('password'),
                'phone' => '+91-9876543214',
                'organization' => 'Indian Coast Guard',
                'agency_type' => 'coast_guard',
                'description' => 'Maritime search and rescue operations along Indian coastline.',
                'latitude' => 13.0827,
                'longitude' => 80.2707,
                'status' => 'active',
            ],
        ];

        $users = [];
        foreach ($agencies as $agency) {
            $users[] = User::create($agency);
        }

        // --- Resources ---
        $resourceData = [
            [$users[0], 'Search & Rescue Team Alpha', 'personnel', 50, 35],
            [$users[0], 'Heavy Rescue Trucks', 'vehicle', 12, 8],
            [$users[0], 'Hydraulic Cutters', 'equipment', 20, 15],
            [$users[1], 'Medical Volunteers', 'personnel', 200, 120],
            [$users[1], 'First Aid Kits', 'medical', 500, 350],
            [$users[1], 'Relief Supply Packs', 'supplies', 1000, 700],
            [$users[2], 'Fire Engines', 'vehicle', 8, 5],
            [$users[2], 'Firefighters', 'personnel', 80, 60],
            [$users[2], 'Fire Extinguishers', 'equipment', 100, 85],
            [$users[3], 'Emergency Doctors', 'personnel', 30, 20],
            [$users[3], 'Ambulances', 'vehicle', 15, 10],
            [$users[3], 'Portable Defibrillators', 'medical', 25, 20],
            [$users[4], 'Rescue Boats', 'vehicle', 6, 4],
            [$users[4], 'Coast Guard Officers', 'personnel', 40, 30],
            [$users[4], 'Life Jackets', 'supplies', 200, 180],
        ];

        foreach ($resourceData as [$user, $name, $type, $qty, $avail]) {
            Resource::create([
                'user_id' => $user->id,
                'name' => $name,
                'type' => $type,
                'quantity' => $qty,
                'available_quantity' => $avail,
                'status' => $avail > 0 ? 'available' : 'deployed',
            ]);
        }

        // --- Calamities ---
        $cal1 = Calamity::create([
            'reported_by' => $users[0]->id,
            'title' => 'Severe Flooding in Gujarat',
            'type' => 'flood',
            'description' => 'Heavy monsoon rains have caused severe flooding in multiple districts of Gujarat. Several villages are submerged and thousands are displaced.',
            'severity' => 'critical',
            'latitude' => 23.0225,
            'longitude' => 72.5714,
            'radius_km' => 50,
            'status' => 'active',
        ]);

        $cal2 = Calamity::create([
            'reported_by' => $users[2]->id,
            'title' => 'Industrial Fire in Pune',
            'type' => 'fire',
            'description' => 'Major fire broke out in an industrial complex in Pune. Multiple warehouses affected.',
            'severity' => 'high',
            'latitude' => 18.5204,
            'longitude' => 73.8567,
            'radius_km' => 5,
            'status' => 'active',
        ]);

        $cal3 = Calamity::create([
            'reported_by' => $users[3]->id,
            'title' => 'Earthquake Tremors in Uttarakhand',
            'type' => 'earthquake',
            'description' => 'Moderate earthquake tremors felt across Uttarakhand region. Magnitude 5.2 on Richter scale.',
            'severity' => 'medium',
            'latitude' => 30.0668,
            'longitude' => 79.0193,
            'radius_km' => 80,
            'status' => 'active',
        ]);

        // --- Alerts ---
        Alert::create([
            'calamity_id' => $cal1->id,
            'created_by' => $users[0]->id,
            'title' => 'URGENT: Deploy Rescue Teams to Gujarat',
            'message' => 'All available rescue teams are requested to deploy to flood-affected areas in Gujarat immediately. Priority zones identified in Ahmedabad and surrounding districts.',
            'priority' => 'critical',
            'is_broadcast' => true,
        ]);

        Alert::create([
            'calamity_id' => $cal2->id,
            'created_by' => $users[2]->id,
            'target_user_id' => $users[3]->id,
            'title' => 'Medical Support Needed for Industrial Fire',
            'message' => 'Requesting immediate medical team deployment to Pune industrial fire site. Estimated 15+ casualties requiring urgent care.',
            'priority' => 'high',
            'is_broadcast' => false,
        ]);

        Alert::create([
            'calamity_id' => $cal3->id,
            'created_by' => $users[3]->id,
            'title' => 'Earthquake Advisory: Prepare for aftershocks',
            'message' => 'All agencies in North India region are advised to prepare for possible aftershocks. Keep rescue equipment ready.',
            'priority' => 'medium',
            'is_broadcast' => true,
        ]);

        // --- Messages ---
        Message::create(['sender_id' => $users[0]->id, 'receiver_id' => $users[1]->id, 'content' => 'Can Red Cross send medical volunteers to Gujarat flood zone?', 'created_at' => now()->subHours(3)]);
        Message::create(['sender_id' => $users[1]->id, 'receiver_id' => $users[0]->id, 'content' => 'Yes, deploying 50 medical volunteers immediately. ETA 6 hours.', 'created_at' => now()->subHours(2)]);
        Message::create(['sender_id' => $users[0]->id, 'receiver_id' => $users[1]->id, 'content' => 'Excellent. We will coordinate at the Ahmedabad staging area.', 'created_at' => now()->subHour()]);

        Message::create(['sender_id' => $users[2]->id, 'receiver_id' => $users[3]->id, 'content' => 'Fire at Pune industrial area. Need ambulances ASAP.', 'created_at' => now()->subHours(5)]);
        Message::create(['sender_id' => $users[3]->id, 'receiver_id' => $users[2]->id, 'content' => 'Dispatching 5 ambulances and emergency medical team now.', 'created_at' => now()->subHours(4)]);

        // --- Location Logs ---
        foreach ($users as $user) {
            if ($user->latitude && $user->longitude) {
                LocationLog::create([
                    'user_id' => $user->id,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                ]);
            }
        }
    }
}
