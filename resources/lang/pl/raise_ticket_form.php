<?php

return [
    'select_department' => 'Wybierz dział z którym chcesz się skontaktować.',
    'device_name' => 'Nazwa urządzenia',
    'username' => 'Nazwa użytkownika',
    'zone_label' => 'Obszar/dział produkcji',
    'zone_select_default' => "Wybierz obszar produkcji",
    'position_label' => 'Stanowisko',
    'position_select_default' => "Wybierz stanowisko",
    'problem_label' => 'Problem',
    'problem_select_default' => "Wybierz problem",
    'message_box_label' => 'Wiadomość (max 500 znaków) (opcjonalnie)',
    'priority_label' => 'Priorytet (opcjonalnie)',
    'priority_low' => 'Powiadomienie',
    'priority_medium' => 'Standardowy',
    'priority_high' => 'Krytyczny',
    'priority' => 'Priorytet',
    'priorities_desc' => 'Skutek',
    'priority_low_desc' => 'Uwagi, pomysły, usprawnienia, modyfikacje, itd.',
    'priority_medium_desc' => 'PRODUKCJA NIE JEST ZAGROŻONA - Usterka powoduje znaczne utrudnienia dla procesu produkcyjnego.',
    'priority_high_desc' => 'PRODUKCJA ZATRZYMANA LUB WYSOKIE RYZYKO ZATRZYMANIA - Interwencja musi być podjęta najszybciej jak to możliwe',
    'submit_form' => 'Wyślij',
    'submitting' => 'Wysyłanie...',
    'ticket_sent_message' => 'Pomyślnie dodano zgłoszenie o numerze <strong><u>:ticketID</u></strong>. <br/>',
    'ticket_sent_acceptance_message' => "<br/> Przekazanie tego zgłoszenia do działu <strong>:targetDepartment</strong>
        odbędzie się po weryfikacji i akceptacji pracownika działu <strong>:department</strong>.<br/>
        Poinformuj przełożonego o swoim zgłoszeniu.",
    'close_form' => 'Zamknij okno',

    /**
     * Teams notifications
     */
    'teams_message_title' => 'Zgłoszenie :ticketID',
    'teams_message_subtitle' => 'Utworzone :dateCreated',
    'teams_message_text' => 'Obszar: :zone. Stanowisko: :position. Problem: :problem.',
    'teams_message_url' => 'Link do zgłoszenia',
];
