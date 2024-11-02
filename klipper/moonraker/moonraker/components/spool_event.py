# Filament spending event 
#add in printer.conf
#[spool_event]
#

from __future__ import annotations
import logging
import requests
import json
import os
from typing import TYPE_CHECKING
if TYPE_CHECKING:
    from typing import Set, Optional, List
    from confighelper import ConfigHelper

printer_id = ''

class SpoolEvent:
    def __init__(self, config: ConfigHelper) -> None:
        self.server = config.get_server()
        global printer_serial 
        global printer_port 
        global sm_db_host
        printer_serial = config.get('printer_serial')
        printer_port = config.get('moonraker_port')
        sm_db_host = config.get('sm_db_host')
        self.server.register_event_handler(
            "history:history_changed", self._history_change)

    def _history_change(self, data: str) -> None:
        action = data['action']
        if action == 'finished':
            job_data = data['job']
            job_id = job_data['job_id']
            filament_used = job_data['filament_used']
            filename = job_data['filename']
            data =  {'printer_serial':printer_serial,
                'lenght':filament_used,
                'filename':filename,
                'printer_port':str(printer_port)}
            requests.post('http://'+sm_db_host+'/sm/put_job.php', data = data) 
            #Возврат потока на 100% по окончанию печати
            run_command = "curl -s -o /dev/null http://localhost:"+printer_port+"/printer/gcode/script?script=M221%20S100 &"
            os.system(run_command)
            run_command = "curl -s -o /dev/null http://localhost:"+printer_port+"/printer/gcode/script?script=SET_RETRACTION%20RETRACT_LENGTH=0%20RETRACT_SPEED=10%20UNRETRACT_EXTRA_LENGTH=0%20UNRETRACT_SPEED=10 &"
            os.system(run_command)
            run_command = "curl -s -o /dev/null http://localhost:"+printer_port+"/printer/gcode/script?script=SET_PRESSURE_ADVANCE%20ADVANCE=0.00 &"
            os.system(run_command)

            logging.info(f"catch job finished - {printer_serial} - {filament_used} - {filename}")
        
        elif action == 'added':
            data =  {'printer_serial':printer_serial,
		    'printer_port':str(printer_port)}
            spool_param_json = requests.post('http://'+sm_db_host+'/sm/get_spool_param.php', data = data)
            spool_param = json.loads(spool_param_json.text)
            flow = str(spool_param.get('flow'))
            RetLen = str(spool_param.get('RetLen'))
            RetSp = str(spool_param.get('RetSp'))
            UnRetExtrLen = str(spool_param.get('UnRetExtrLen'))
            UnRetSp = str(spool_param.get('UnRetSp'))
            PA = str(spool_param.get('PA'))
            #установка потока из базы при начале печати
            run_command = "curl -s -o /dev/null http://localhost:"+printer_port+"/printer/gcode/script?script=M221%20S"+flow+"&"
            os.system(run_command)
            #установка ретрактов из базы при начале печати
            run_command = "curl -s -o /dev/null http://localhost:"+printer_port+"/printer/gcode/script?script=SET_RETRACTION%20RETRACT_LENGTH="+RetLen+"%20RETRACT_SPEED="+RetSp+"%20UNRETRACT_EXTRA_LENGTH="+UnRetExtrLen+"%20UNRETRACT_SPEED="+UnRetSp+"&"
            os.system(run_command)
            run_command = "curl -s -o /dev/null http://localhost:"+printer_port+"/printer/gcode/script?script=SET_PRESSURE_ADVANCE%20ADVANCE="+PA+"&"
            os.system(run_command)
            logging.info(f"catch job started - {printer_serial} - {printer_port} - {flow} - "+PA)


def load_component(config: ConfigHelper) -> SpoolEvent:
    return SpoolEvent(config)
