alter table dlvry_libbookfinedet add column voucherEntryId int after paid_on ,add foreign key(voucherEntryId) references dlvry_voucherentry(es_voucherentryid) on delete cascade ;