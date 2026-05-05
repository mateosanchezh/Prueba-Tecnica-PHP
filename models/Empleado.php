<?php

class Empleado
{
    private ?int    $id;
    private ?string $nombre;
    private ?string $apellido;
    private ?string $cargo;
    private ?float  $salario;
    private ?string $fechaIngreso;
    private ?string $fechaCreacion;

    public function __construct(
        ?int    $id            = null,
        ?string $nombre        = null,
        ?string $apellido      = null,
        ?string $cargo         = null,
        ?float  $salario       = null,
        ?string $fechaIngreso  = null,
        ?string $fechaCreacion = null
    ) {
        $this->id            = $id;
        $this->nombre        = $nombre;
        $this->apellido      = $apellido;
        $this->cargo         = $cargo;
        $this->salario       = $salario;
        $this->fechaIngreso  = $fechaIngreso;
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getId(): ?int       { return $this->id; }
    public function getNombre(): ?string    { return $this->nombre; }
    public function getApellido(): ?string  { return $this->apellido; }
    public function getCargo(): ?string     { return $this->cargo; }
    public function getSalario(): ?float    { return $this->salario; }
    public function getFechaIngreso(): ?string  { return $this->fechaIngreso; }
    public function getFechaCreacion(): ?string { return $this->fechaCreacion; }
}
