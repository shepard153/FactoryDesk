<?php

return [
    'page_title' => 'Zgłoszenia',

    /**
     * Tickets list
     */
    'filter_awaiting' => 'Do zatwierdzenia',
    'filter_new' => 'Nowe',
    'filter_in_progress' => 'W realizacji',
    'filter_closed' => 'Zamknięte',
    'filter_active' => 'Aktywne',
    'pagination_displaying' => 'Wyświetlane :start - :end z :total wyników.',
    'pagination_of' => 'z :last_page',
    'table_ID' => 'ID',
    'table_status' => 'Status',
    'table_zone' => 'Obszar',
    'table_position' => 'Stanowisko',
    'table_problem' => 'Problem',
    'table_device' => 'Urządzenie',
    'table_date_created' => 'Data zgłoszenia',
    'table_date_modified' => 'Data modyfikacji',
    'table_date_closed' => 'Data zamknięcia',
    'table_owner' => 'Osoba odpowiedzialna',
    'table_nothing_found' => 'Nie znaleziono zgłoszeń.',

    /**
     * Ticket detials
     */
    'ticket_header' => 'Szczegóły zgłoszenia',
    'awaiting_header' => 'Zgłoszenie oczekuja na zatwierdzenie',
    'awaiting_accept_desc' => "<b>Zatwierdź zgłoszenie</b> - zgłoszenie zostanie przesłane do działu wskazanego w polu <b>Dział</b>.",
    'awaiting_reject_desc' => '<b>Odrzuć zgłoszenie</b> - zgłoszenie zostanie zamknięte.',
    'awaiting_take_desc' => '<b>Podejmij zgłoszenie</b> - w wypadku, gdy zgłoszenie możesz rozwiązać sam.',
    'navtab_ticket' => 'Zgłoszenie',
    'navtab_add_note' => 'Dodaj notatkę',
    'navtab_ticket_history' => 'Historia zgłoszenia',
    'date_taken' => 'Data podjęcia',
    'pill_closed_permamently' => 'Zamknięte premanentnie',
    'raised_by' => 'Zgłaszający',
    'department' => 'Dział',
    'priority' => 'Priorytet',
    'priority_low' => 'Powiadomienie',
    'priority_medium' => 'Standardowy',
    'priority_high' => 'Krytyczny',
    'external_ticket' => 'Zgłoszenie zewnętrzne',
    'time_spent_on' => 'Czas obsługi zlecenia',
    'timer_minutes_button' => 'minut',
    'accept_ticket' => 'Zatwierdź zgłoszenie',
    'reject_ticket' => 'Odrzuć zgłoszenie',
    'take_ticket' => 'Podejmij zgłoszenie',
    'save_button' => 'Zapisz zmiany',
    'close_ticket' => 'Zamknij zgłoszenie',
    'reopen_ticket' => 'Otwórz ponownie zgłoszenie',
    'modal_confirm' => 'Potwierdź',
    'modal_cancel' => 'Anuluj',
    'close_ticket_note' => 'Dodaj krótką notatkę przed zamknięciem zgłoszenia (do 250 znaków)',
    'accept_ticket_note' => 'Dodaj krótką notatkę przed zatwierdzeniem zgłoszenia (do 250 znaków)',
    'attachments' => 'Załączniki',
    'download_attachment' => 'Pobierz załącznik',
    'no_attachments' => 'Brak załączników',
    'ticket_message' => 'Wiadomość do zgłoszenia',
    'no_message' => 'Brak wiadomości',
    'add_note_label' => 'Notatka (do 250 znaków)',
    'add_note_button' => 'Dodaj notatkę',
    'note' => 'Notatka utworzona :created_at przez :username',
    'edit_header' => 'Edytowane przez :username dnia :date',
    'ticket_type' => 'Typ zgłoszenia',
    'ticket_type_valid' => 'Zasadne',
    'ticket_type_invalid' => 'Niezasadne',

    /**
     * Ticket action notifications
     */
    'ticket_taken' => 'Podjęto zgłoszenie',
    'ticket_closed' => 'Zgłoszenie zostało zamknięte',
    'ticket_rejected' => 'Zgłoszenie zostało odrzucone',
    'ticket_accepted' => 'Zgłoszenie zostało zatwierdzone',
    'ticket_reopened' => 'Zgłoszenie zostało otwarte ponownie',
    'ticket_saved' => 'Zmiany zostały zapisane',
    'note_added' => 'Dodano notatkę',

    /**
     * History messages
     */
    'taken_by' => 'Zgłoszenie podjęte przez :username',
    'closed_by' => 'Zgłoszenie zamknięte przez :username',
    'reopened_by' => 'Zgłoszenie ponownie otwarte przez :username',
    'rejected_by' => 'Zgłoszenie odrzucone przez :username',
    'accepted_by' => 'Zgłoszenie zatwierdzone przez :username i przesłane do działu :department z ID: :ticketID',
    'department_changed' => 'Zmieniono dział z :original na :new',
    'problem_changed' => 'Zmieniono problem z :original na :new',
    'priority_changed' => 'Zmieniono priorytet z :original na :new',
    'owner_changed' => 'Zmieniono osobę odpowiedzialną z :original na :new',
    'external_ticket_set' => 'Zgłoszenie zostało ustawione jako zewnętrzne z ID: :externalID',

    /**
     * My tickets
     */
    'my_tickets_page_title' => 'Moje zgłoszenia',
    'my_statistics' => 'Moje statystyki',
    'last_taken' => 'Ostatnio podjęte zgłoszenia',
    'all_taken' => 'Wszystkie podjęte zgłoszenia',
    'last_closed' => 'Ostatnio zamknięte zgłoszenia',
    'all_closed' => 'Wszystkie zamknięte zgłoszenia',
];
