<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Enums\ProjectStatus;
use App\Domain\Ticket\Enums\TicketPriority;
use App\Domain\Ticket\Enums\TicketStatus;
use App\Domain\Ticket\Enums\TicketType;
use App\Infrastructure\Authentication\Models\User;
use App\Infrastructure\Comment\Models\Comment;
use App\Infrastructure\Project\Models\Project;
use App\Infrastructure\Project\Models\ProjectMember;
use App\Infrastructure\Ticket\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a User
        $user = User::create([
            'name'     => 'Dummy User',
            'email'    => 'dummy@example.com',
            'password' => 'password', // Mutator in User model or just plain if not using mutator? Oh wait, User has casts password => hashed.
        ]);

        // 2. Create a Project
        $project = Project::create([
            'owner_id'    => $user->id,
            'name'        => 'Dummy Project',
            'slug'        => Str::slug('Dummy Project'),
            'description' => 'This is a description for the dummy project.',
            'status'      => ProjectStatus::ACTIVE,
        ]);

        // 3. Create a Project Member
        ProjectMember::create([
            'project_id' => $project->id,
            'user_id'    => $user->id,
            'role'       => UserRole::ADMIN,
            'joined_at'  => Carbon::now(),
        ]);

        // 4. Create a Ticket
        $ticket = Ticket::create([
            'project_id'    => $project->id,
            'reporter_id'   => $user->id,
            'assignee_id'   => $user->id,
            'ticket_number' => 1,
            'title'         => 'First Dummy Ticket',
            'description'   => 'This is a detailed description of the dummy ticket.',
            'type'          => TicketType::ISSUE,
            'status'        => TicketStatus::OPEN,
            'priority'      => TicketPriority::HIGH,
        ]);

        // 5. Create a Comment
        Comment::create([
            'ticket_id'   => $ticket->id,
            'author_id'   => $user->id,
            'body'        => 'This is an example comment on the dummy ticket.',
            'is_internal' => false,
            'created_at'  => Carbon::now(),
        ]);
    }
}
