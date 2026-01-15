<?php $__env->startSection('titulo', 'Listado'); ?>
<?php $__env->startSection('contenido'); ?>
    <div class="d-flex justify-content-between mb-3">
        <h3>Gestión de Jugadores</h3>
        <a href="fcrear.php" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo Jugador</a>
    </div>
    <table class="table table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nombre Completo</th>
                <th>Posición</th>
                <th>Dorsal</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $arrayJugadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="align-middle">
                <td><?php echo e($j['nombre']); ?> <?php echo e($j['apellidos']); ?></td>
                <td><span class="badge bg-info text-dark"><?php echo e($j['posicion']); ?></span></td>
                
                <td><?php echo e(($j['dorsal'] == 0) ? 'Sin asignar' : $j['dorsal']); ?></td>
                
                <td class="text-center">
                    <a href="jugadores.php?id_borrar=<?php echo e($j['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres borrar al jugador <?php echo e($j['nombre']); ?>?')">
                        <i class="fas fa-trash"></i> Borrar
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantilla1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>