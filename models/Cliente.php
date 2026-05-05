<?php

class Cliente
{
    private ?int    $id;
    private ?string $nombre;
    private ?string $apellido;
    private ?string $email;
    private ?string $telefono;
    private ?string $direccion;
    private ?string $fechaCreacion;

    public function __construct(
        ?int    $id            = null,
        ?string $nombre        = null,
        ?string $apellido      = null,
        ?string $email         = null,
        ?string $telefono      = null,
        ?string $direccion     = null,
        ?string $fechaCreacion = null
    ) {
        $this->id            = $id;
        $this->nombre        = $nombre;
        $this->apellido      = $apellido;
        $this->email         = $email;
        $this->telefono      = $telefono;
        $this->direccion     = $direccion;
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getId(): ?int       { return $this->id; }
    public function getNombre(): ?string    { return $this->nombre; }
    public function getApellido(): ?string  { return $this->apellido; }
    public function getEmail(): ?string     { return $this->email; }
    public function getTelefono(): ?string  { return $this->telefono; }
    public function getDireccion(): ?string { return $this->direccion; }
    public function getFechaCreacion(): ?string { return $this->fechaCreacion; }
}
