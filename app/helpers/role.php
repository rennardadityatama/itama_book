<?php
function roleLabel($role) {
  return [
    'admin' => 'Administrator',
    'seller' => 'Seller',
    'customer' => 'Customer'
  ][$role] ?? '-';
}
