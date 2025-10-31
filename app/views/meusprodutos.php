<?php
require_once __DIR__ . '/../model/Admin.php';
require_once __DIR__ . '/../model/Loja.php';

?>

<table class="table table-striped table-hover mt-3 text-center align-middle">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Cor</th>
            <th>Data de modificação</th>
            <th>Info</th>
            <th>Modificar</th>
            <th>Deletar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>nome do produto</td>
            <td>preço do produto</td>
            <td>cor do produto</td>
            <td>data de modificação</td>
            <td>
                <button class="btn btn-sm">
                    <i class="bi bi-list fs-3"></i>
                </button>
            </td>
            <td>
                <button class="btn btn-sm">
                    <i class="bi bi-gear fs-3"></i>
                </button>
            </td>
            <td>
                <button class="btn btn-sm">
                    <i class="bi bi-trash fs-3"></i>
                </button>
            </td>
        </tr>
    </tbody>
</table>