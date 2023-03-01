# Filament spending event 
#add in printer.conf
#[spool_event]
#

from __future__ import annotations
import logging
import requests
from typing import TYPE_CHECKING
if TYPE_CHECKING:
    from typing import Set, Optional, List
    from confighelper import ConfigHelper

printer_id = ''

class SpoolEvent:
    def __init__(self, config: ConfigHelper) -> None:
        self.server = config.get_server()
        global printer_serial 
        printer_serial = config.get('printer_serial')
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
	            'filename':filename}

            requests.post('http://localhost/sm/put_job.php', data = data) 
            logging.info(f"catch job finished - {printer_serial} - {filament_used} - {filename}")




def load_component(config: ConfigHelper) -> SpoolEvent:
    return SpoolEvent(config)
