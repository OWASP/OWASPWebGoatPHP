INSERT INTO `PREFIX_rbac_permissions` (`ID`, `Lft`, `Rght`, `Title`, `Description`) VALUES
(1, 1, 2, 'root', 'root');

INSERT INTO `PREFIX_rbac_rolepermissions` (`RoleID`, `PermissionID`, `AssignmentDate`) VALUES
(1, 1, 0);

INSERT INTO `PREFIX_rbac_roles` (`ID`, `Lft`, `Rght`, `Title`, `Description`) VALUES
(1, 1, 2, 'root', 'root');

INSERT INTO `PREFIX_rbac_userroles` (`UserID`, `RoleID`, `AssignmentDate`) VALUES
(1, 1, 0);

INSERT INTO `PREFIX_users` (`ID`, `Username`, `Password`, `Salt`, `Protocol`) VALUES
(1, 'root', '09095c2a72470b8a203fb4bc5193c548802177b9f786ce1c5c0f423f68ed2518c8ce4c536240fc25be7cae06fc1860f2ec9c3b469770244837b91689c7a0c9a5', 'dff41c6629ca83aee37987d3363ce8c9d17b3c3511797956de9abf3535132d9c2daf1a59a32df97e2adafcbe707c989b9957f44ae336f6dc4ada6ef121eeebf4', 1);

INSERT INTO `PREFIX_xuser` (`ID`,`Email`,`CreateTimestamp`,`Activated`) VALUES
(1, 'root@localhost','0','1');
