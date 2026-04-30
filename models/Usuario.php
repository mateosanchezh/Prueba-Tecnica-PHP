<?php

class Usuario
{
    private ?int    $id;
    private ?string $username;
    private ?string $passwordHash;
    private ?string $nombre;

    public function __construct(
        ?int    $id           = null,
        ?string $username     = null,
        ?string $passwordHash = null,
        ?string $nombre       = null
    ) {
        $this->id           = $id;
        $this->username     = $username;
        $this->passwordHash = $passwordHash;
        $this->nombre       = $nombre;
    }

    public function getId(): ?int       { return $this->id; }
    public function getUsername(): ?string    { return $this->username; }
    public function getPasswordHash(): ?string { return $this->passwordHash; }
    public function getNombre(): ?string { return $this->nombre; }
}
