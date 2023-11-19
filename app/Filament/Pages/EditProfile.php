<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $detailsData = [];
    public User $user;

    protected static ?string $navigationIcon = "heroicon-o-document-text";

    protected static ?string $title = "Edit Profile";

    protected ?string $heading = "Edit Profile";

    protected static ?string $slug = "profile";

    protected static string $view = "filament.pages.edit-profile";


    public function mount(): void
    {
        $this->user = auth()->user();

        $this->detailsForm->fill([
            "name" => $this->user->name,
            "email" => $this->user->email,
        ]);
    }

    protected function getForms(): array
    {
        return ["detailsForm", "updatePasswordForm"];
    }

    public function detailsForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Details")->schema([
                    TextInput::make("name")
                        ->label("Name")
                        ->required()
                        ->maxLength(255),
                    TextInput::make("email")
                        ->label("Email")
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                ]),
            ])
            ->statePath("detailsData")
            ->model(auth()->user());
    }

    public function updatePasswordForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Password")->schema([
                    TextInput::make("current_password")
                        ->label("Current password")
                        ->password()
                        ->currentPassword()
                        ->required()
                        ->maxLength(255),
                    TextInput::make("new_password")
                        ->label("New password")
                        ->password()
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    TextInput::make("password_confirmation")
                        ->label("Password confirmation")
                        ->password()
                        ->same("new_password")
                        ->required()
                        ->maxLength(255),
                ]),
            ])
            ->statePath("detailsData")
            ->model(auth()->user());
    }

    public function saveDetailsForm(): void
    {
        $this->detailsForm->getState();

        $this->user->update($this->detailsData);

        $this->dispatch("user-updated");

        $this->updatePasswordForm->fill();
        $this->detailsForm->fill([
            "name" => $this->user->name,
            "email" => $this->user->email,
        ]);

        Notification::make()
            ->title("Saved")
            ->success()
            ->send();
    }

    public function saveUpdatePasswordForm(): void
    {
        $state = $this->updatePasswordForm->getState();

        $this->user->update([
            "password" => Hash::make($state["new_password"]),
        ]);

        request()
            ->session()
            ->put([
                "password_hash_" .
                auth()->getDefaultDriver() => $this->user->getAuthPassword(),
            ]);

        $this->updatePasswordForm->fill();
        $this->detailsForm->fill([
            "name" => $this->user->name,
            "email" => $this->user->email,
        ]);

        Notification::make()
            ->title("Saved")
            ->success()
            ->send();
    }
}
