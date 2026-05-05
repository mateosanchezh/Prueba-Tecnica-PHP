<?php

class Producto
{
    private ?int    $id;
    private ?string $nombre;
    private ?string $descripcion;
    private ?float  $precio;
    private ?int    $stock;
    private ?string $categoria;
    private ?string $fechaCreacion;

    public function __construct(
        ?int    $id            = null,
        ?string $nombre        = null,
        ?string $descripcion   = null,
        ?float  $precio        = null,
        ?int    $stock         = null,
        ?string $categoria     = null,
        ?string $fechaCreacion = null
    ) {
        $this->id            = $id;
        $this->nombre        = $nombre;
        $this->descripcion   = $descripcion;
        $this->precio        = $precio;
        $this->stock         = $stock;
        $this->categoria     = $categoria;
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getId(): ?int       { return $this->id; }
    public function getNombre(): ?string     { return $this->nombre; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getPrecio(): ?float      { return $this->precio; }
    public function getStock(): ?int         { return $this->stock; }
    public function getCategoria(): ?string  { return $this->categoria; }
    public function getFechaCreacion(): ?string { return $this->fechaCreacion; }
}
