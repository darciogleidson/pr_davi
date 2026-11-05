<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

#[Signature('app:create-admin-user')]
#[Description('Cria ou atualiza a conta administrativa da aplicação.')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = trim((string) $this->ask('Nome do responsável'));
        $email = mb_strtolower(trim((string) $this->ask('E-mail do responsável')));
        $password = (string) $this->secret('Senha do responsável (mínimo de 12 caracteres)');
        $passwordConfirmation = (string) $this->secret('Confirme a senha');

        $admin = User::query()->first();
        $emailRule = Rule::unique('users', 'email');

        if ($admin !== null) {
            $emailRule->ignore($admin->getKey());
        }

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ], [
            'password.min' => 'A senha deve ter pelo menos 12 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'email.unique' => 'Já existe uma conta com esse e-mail.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $validated = $validator->validated();
        $admin ??= new User;
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->password = $validated['password'];
        $admin->save();

        $this->info('Conta administrativa criada ou atualizada com sucesso.');

        return self::SUCCESS;
    }
}
