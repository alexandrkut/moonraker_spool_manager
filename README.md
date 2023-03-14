Менеджер катушек для клиппера.

В конфиге moonraker.conf нужно добавить секцию

[spool_event]<br>
printer_serial: UltiSteel
moonraker_port: 7125

Серийный номер принтера и порт Moonraker нужны для многопринтерных систем.

Функции:
1. Учет завтрат пластика на разных катушках
2. Автоматическое выставление потока в зависимости от выбранной катушки (например есть 5 катушек одинакового пластика, но поток у всех катушек разный)

Сами принтеры добавляются напрямую в базу sqlite (интерфейса для этого нет, так как это оооочень не частая операция).

При начале печати из базы забирается параметр поток для текущей катушки установленной на принтере.

По окончанию печати данные отправляются на url (задается в klipper/moonraker/moonraker/components/spool_event.py).
В данных содержится:
1. Идентификатор принтера
2. Количество используемого филамента в мм (надо уточнить единицу измерения, но вроде в мм).
3. Имя файла.

После этого поток выставляется в 100%

Менеджер катушек пересчитывает длину в граммы на основе заданой плотности в свойствах материала, но считает что филамент диаметром 1.75 (можно добавить передачу сразу в граммах)
и отнимает ее от веса текущей катушки.

Так же менеджер катушек сохраняет историю печати с количество потраченного филамента.





П.с. код написан на коленке, не претендует на качество и красоту.
