<h2>User List</h2>
<table border="1">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
<?php foreach($users as $u): ?>
<tr>
  <td><?= $u['id'] ?></td>
  <td><?= $u['name'] ?></td>
  <td><?= $u['email'] ?></td>
  <td><?= $u['role'] ?></td>
</tr>
<?php endforeach; ?>
</table>
