-----read me-----
secara otomatis role level saat registrasi adalah bukan sebagai admin, untuk merubah
role level dari non-admin menjadi admin dapat dilakukan dengan cara merubah/update
pada table users dengan field role, yang tadinya bernilai 0 menjadi 1
------query--------
update users set role = 1 where email = 'xx@xx.xx';
