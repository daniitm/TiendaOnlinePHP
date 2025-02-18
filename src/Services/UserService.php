<?php
namespace Services;

use Repositories\UserRepository;
use Models\User;

class UserService {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function registerUser(User $user) {
        return $this->userRepository->registerUser($user);
    }

    public function getUserByEmail(string $email): ?User {
        return $this->userRepository->getUserByEmail($email);
    }

    public function getUserEmailById(int $userId): ?string {
        return $this->userRepository->getUserEmailById($userId);
    }

    public function updatePassword(string $email, string $newPassword): bool {
        return $this->userRepository->updatePassword($email, $newPassword);
    }

    public function setResetToken(string $email, string $token, int $expiry): bool {
        return $this->userRepository->setResetToken($email, $token, $expiry);
    }

    public function getUserByResetToken(string $token): ?User {
        return $this->userRepository->getUserByResetToken($token);
    }

    public function isTokenExpired(User $user): bool {
        return $user->getResetTokenExpiry() < time();
    }

    public function updatePasswordAndClearToken(string $email, string $newPassword): bool {
        return $this->userRepository->updatePasswordAndClearToken($email, $newPassword);
    }

    public function getUserByVerificationToken(string $token): ?User {
        return $this->userRepository->getUserByVerificationToken($token);
    }
    
    public function verifyUser(string $email): bool {
        return $this->userRepository->verifyUser($email);
    }
}