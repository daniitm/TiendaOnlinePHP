<?php
    namespace Services;

    use Repositories\UserRepository;
    use Models\User;

    class UserService{
        private UserRepository $userRepository;


        public function __construct(){
            $this->userRepository = new UserRepository();
        }

        public function registerUser(User $user){
            return $this->userRepository->registerUser(usuario: $user);
        }

        public function getUserbyEmail(string $email): ?User {
            return $this->userRepository->getUserbyEmail($email);
        }

        public function getUserEmailById(int $userId): ?string {
            return $this->userRepository->getUserEmailById($userId);
        }
    }

