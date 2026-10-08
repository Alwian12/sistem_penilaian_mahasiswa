USE triguna_akademik;

ALTER TABLE krs
    ADD COLUMN dosen_id INT DEFAULT NULL AFTER mahasiswa_id,
    ADD INDEX idx_krs_dosen (dosen_id),
    ADD CONSTRAINT fk_krs_dosen FOREIGN KEY (dosen_id) REFERENCES dosen(id);

UPDATE krs k
JOIN nilai n ON n.krs_id = k.id
SET k.dosen_id = n.dosen_id
WHERE k.dosen_id IS NULL AND n.dosen_id IS NOT NULL;