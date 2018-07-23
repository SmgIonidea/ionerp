
alter table  dlvry_trans_maintenance add column voucherId int after tr_maintenance_type, add foreign key(voucherId) references dlvry_voucher(es_voucherid) on delete cascade ;	

alter table  dlvry_trans_maintenance add column legderId int after voucherId, add foreign key(legderId) references dlvry_ledger(es_ledgerid) on delete cascade ;

alter table dlvry_trans_maintenance add column voucherEntryId int after voucherId ,add foreign key(voucherEntryId) references dlvry_voucherentry(es_voucherentryid) on delete cascade ;