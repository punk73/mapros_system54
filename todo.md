## 2020 02 29

- number increment (done)
- sisa dan total output di remark OCS (done)
- Serial number rata kiri. (done)
- buat dokumen panduan untuk download

## working on new reworks features

- what we want ?
  - we can do a rework, without doing a "pre work" ?
- what kind of problem will we face ??

  - untuk sekedar tidak cek previous step, we already finish. the problem here is :

    - join processs: already joined parts, cannot being scan anymore.
      on the previous solutions, we backup the data to a new tables, and delete on the original one.

    - how to make sure that we scan the right parts ??

## todo's today 2020-04-07

- make new setting, for testing to identify current behaviour that contain :
  - scan board, join board with master
  - scan ticket with board
  - scan master with ticket

## todo's today 2020-04-08

- gimana caranya, kita bisa scan boards yg sudah join. ??

  - current :

    - jika boards sudah ada guid ticket atau guid master, artinya sudah join dengan data lain. throw exception.

      - kita cek dulu sebelum throw exception :

        - apakah isRework aktif, kalau ya, maka harusnya data boards yang sebelumnya, guidnya ganti dengan guid baru.
        - yg lama ?? pindahkan ke reworks backup ??
          - kalau salah ?? apakah bisa undo ?? - engga, paling scan ulang pake data yang bener.

      - ada problem, meskipun kita sudah backup, dan update data boardnya, scan in scan out di process joinnya tetap ada.
        jadi tampilan front end nya tetap warna merah, ketika seharusnya warna hijau.

    - kita harus pikirkan juga bagaimana caranya repair dalam kasus seperti ini.

* gimana caranya kita bisa scan ticket yang sudah join. ??

  - ticket ini mengandung 2 data penting :
    - board - table boards
    - lcd module - table parts

  ketika rework, sebetulnya ga lagi menggunakan dummy_ticket.
  jadi dia tetap menggunakan dummy ticket baru.
  masalahnya, kalau ticket tidak start dari panel 1, data lcd dan data boards pun ga ada.
  akhirnya, percuma aja scan dummy ticket. karena dummy tersebut, ga lagi contain data board & panel.

  how to overcome that problem ??

  gimana, kalau user mau mulai dari pertengahan setelah join lcd & board ??

  answer : ya gapapa, ticket yang di scan, cuman buat "syarat" join ke main aja. toh, ketika nanti main di assign Serial No,
  Serial no tsb tetep bisa konek ke masing2 guid ko. data ga ilang dan tetep bisa di trace. ( kecuali SN nya berubah )

* gimaana caranya kalau SN nya berubah ??
  the whole system will be join together after the set finish process and get the same serial number as the previous SN.

  if SN is changes, we need to find a way, to identify SN history.

  to overcome this issue, we need to make sure if the old SN is will be used again or not.
  if not, it'll be easier for us. we just need to update the old SN to new SN.

  the new problem raise here, how we do that ? how and when we know that this particular SN is new SN for that specific old SN ??
  if we knew, how to match it up ??

- do we need to change current isRework method ?

* current isRework method is checking rework parameter from front end, and wheter the guid is generated.

  - the isGuidGenerated == false, then isRewok feature can be activated.
    if not, it can't due to we need to protect the "already joined board" so that it cannot be join twice.

  - for the sake of this current update, we need to revise this. we will update whatever board the user scan with a new guid
    so that it has new guid.

    the different is, we backup current guid first.
