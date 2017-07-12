require('../scss/main.scss');

import React from 'react';
import { render } from 'react-dom';
import InfoWidget from "./components/InfoWidget";

render(
    <InfoWidget title="Info" text="Simple application allowing anyone to<br>register, login and edit his account information."/>,
    document.getElementById('info-widget')
);
