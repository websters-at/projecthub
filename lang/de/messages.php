<?php

return [

    'call_note' => [
        'resource' => [
            'group' => 'Anrufnotizen',
            'name' => 'Anrufnotiz',
            'name_plural' => 'Anrufnotizen',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'field_name' => 'Titel der Anrufnotiz',
            'field_description' => 'Beschreibung',
            'field_call_id' => 'Anruf',
        ],
        'table' => [
            'name' => 'Titel',
            'description' => 'Beschreibung',
            'call' => 'Anruf',
            'filter_call' => 'Anruf filtern',
        ],
    ],
    'call' => [
        'resource' => [
            'group' => 'Anrufe',
            'name' => 'Anruf',
            'name_plural' => 'Anrufe',
        ],
        'stats' => [
            'today' => 'Anrufe Heute',
            'today_description' => 'Gesamtzahl der für heute geplanten Anrufe.',
            'upcoming' => 'Kommende Anrufe',
            'upcoming_description' => 'Gesamtzahl der für die nächsten 3 Tage geplanten Anrufe.',
            'open' => 'Offene Anrufe',
            'open_description' => 'Gesamtzahl der Anrufe, die nicht als "erledigt" markiert sind.',
        ],
        'form' => [
            'field_customer'=>'Kunde',
            'section_general' => 'Allgemein',
            'section_contract' => 'Auftrag',
            'field_name' => 'Name',
            'field_description' => 'Beschreibung',
            'field_is_done' => 'Erledigt',
            'field_on_date' => 'Datum',
            'field_contract' => 'Auftrag',
        ],
        'table' => [
            'field_on_date' => 'Datum',
            'field_customer' => 'Kunde',
            'field_contract' => 'Auftrag',
            'field_user' => 'Benutzer',
            'field_is_done' => 'Erledigt',
            'filter_is_done' => 'Erledigt Status',
            'filter_on_date' => 'Datum Filter',
            'filter_from' => 'Von',
            'filter_until' => 'Bis',
            'filter_contract' => 'Auftrag',
            'filter_customer' => 'Kunde'
        ],
    ],
    'contract_note' => [
        'resource' => [
            'group' => 'Aufträge', // Navigation group
            'name' => 'Auftragsnotiz', // Singular resource name
            'name_plural' => 'Auftragsnotizen', // Plural resource name
        ],
        'form' => [
            'section_general' => 'Allgemeine Informationen',
            'section_contract' => 'Auftrag',
            'field_name' => 'Titel der Notiz',
            'field_description' => 'Beschreibung',
            'field_date' => 'Datum',
            'field_attachments' => 'Anhänge',
            'field_contract' => "Auftrag",
        ],
        'table' => [
            'name' => 'Titel',
            'date' => 'Datum',
            'contract' => 'Auftrag',
            'customer' => 'Kunde',
        ],
    ],
    'contract' => [
        'resource' => [
            'group' => 'Aufträge',
            'name' => 'Auftrag',
            'name_plural' => 'Aufträge',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'section_location' => 'Standort',
            'section_customer' => 'Kunde',
            'section_employees' => 'Mitarbeiter',
            'section_attachments' => 'Anhänge',
            'field_name' => 'Name',
            'field_description' => 'Beschreibung',
            'field_priority' => 'Priorität',
            'field_due_to' => 'Fällig am',
            'field_is_finished' => 'Erledigt',
            'field_customer' => 'Kunde',
            'field_users' => 'Mitarbeiter',
            'field_country' => 'Land',
            'field_state' => 'Bundesland',
            'field_city' => 'Stadt',
            'field_zip_code' => 'PLZ',
            'field_address' => 'Adresse',
            'field_attachments' => 'Anhänge',
        ],
        'table' => [
            'name' => 'Name',
            'description' => 'Beschreibung',
            'priority' => 'Priorität',
            'due_to' => 'Fällig am',
            'is_finished' => 'Erledigt',
            'customer' => 'Kunde',
            'filter_priority' => 'Priorität',
            'filter_customer' => 'Kunde',
            'filter_users' => 'Mitarbeiter',
            'filter_due_to' => 'Fälligkeitszeitraum',
            'filter_is_finished' => 'Erledigt',
            'filter_name' => 'Firmenname des Auftrags',
        ],
    ],
    'customer' => [
        'resource' => [
            'group' => 'Aufträge',
            'name' => 'Kunde',
            'name_plural' => 'Kunden',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'section_address' => 'Adresse',
            'field_full_name' => 'Vollständiger Name',
            'field_company_name' => 'Firmenname',
            'field_email' => 'E-Mail',
            'field_phone' => 'Telefon',
            'field_tax_id' => 'Steuer-ID',
            'field_country' => 'Land',
            'field_state' => 'Bundesland',
            'field_city' => 'Stadt',
            'field_zip_code' => 'PLZ',
            'field_address' => 'Adresse',
        ],
        'table' => [
            'company_name' => 'Firmenname',
            'email' => 'E-Mail',
            'phone' => 'Telefon',
            'city' => 'Stadt',
            'filter_country' => 'Land',
            'filter_state' => 'Bundesland',
            'filter_city' => 'Stadt',
        ],
    ],
    'login_credentials' => [
        'resource' => [
            'group' => 'Aufträge',
            'name' => 'Login-Daten',
            'name_plural' => 'Login-Daten',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'field_name' => 'Name',
            'field_description' => 'Beschreibung',
            'field_email' => 'E-Mail',
            'field_password' => 'Passwort',
            'field_attachments' => 'Anhänge',
            'section_contracts' => 'Aufträge', // Added section_contracts

        ],
        'table' => [
            'name' => 'Name',
            'contracts' => 'Aufträge',
            'description' => 'Beschreibung',
        ],
        'filter' => [
            'email' => [
                'label' => 'E-Mail-Domain',
                'placeholder' => 'Geben Sie die Domain ein (z.B. example.com)',
            ],
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Nach Namen suchen',
            ],
        ],
    ],
    'permission' => [
        'resource' => [
            'group' => 'Einstellungen',
            'name' => 'Berechtigung',
            'name_plural' => 'Berechtigungen',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'field_name' => 'Name',
        ],
        'table' => [
            'name' => 'Name',
        ],
    ],
    'user' => [
        'resource' => [
            'group' => 'Einstellungen',
            'name' => 'Benutzer',
            'name_plural' => 'Benutzer',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'field_name' => 'Name',
            'field_email' => 'E-Mail',
            'field_password' => 'Passwort',
            'field_roles' => 'Rollen',
        ],
        'table' => [
            'name' => 'Name',
            'email' => 'E-Mail',
            'created_at' => 'Erstellt am',
            'updated_at' => 'Aktualisiert am',
        ],
    ],
    'role' => [
        'resource' => [
            'group' => 'Einstellungen',
            'name' => 'Rolle',
            'name_plural' => 'Rollen',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'field_name' => 'Name',
            'field_permissions' => 'Berechtigungen',
        ],
        'table' => [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Erstellt am',
        ],
    ],
    'todo' => [
        'resource' => [
            'group' => 'Aufträge',
            'name' => 'To-Do',
            'name_plural' => 'To-Dos',
        ],
        'stats' => [
            'today' => 'Todos Heute',
            'today_description' => 'Gesamtzahl der für heute geplanten Todos.',
            'upcoming' => 'Kommende Todos',
            'upcoming_description' => 'Gesamtzahl der für die nächsten 3 Tage geplanten Todos.',
            'open' => 'Offene Todos',
            'open_description' => 'Gesamtzahl der Todos, die nicht als "erledigt" markiert sind.',
        ],
        'form' => [
            'section_general' => 'Allgemein',
            'field_name' => 'Name',
            'field_due_to' => 'Fälligkeitsdatum',
            'field_description' => 'Beschreibung',
            'field_is_done' => 'Abgeschlossen',
            'field_priority_label' => 'Priorität',
            'field_priority' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ],
            'field_attachments' => 'Anhänge',
            'section_contract' => 'Aufträge',
            'field_contract_classification' => 'Auftrag',
        ],
        'table' => [
            'name' => 'Name',
            'contract' => 'Auftrag',
            'customer' => 'Kunde',
            'due_to' => 'Fälligkeitsdatum',
            'priority' => 'Priorität',
            'is_done' => 'Abgeschlossen',
        ],
        'filters' => [
            'user' => [
                'label' => 'Benutzer',
                'placeholder' => 'Benutzer auswählen',
            ],
            'contract' => [
                'label' => 'Auftrag',
                'placeholder' => 'Auftrag auswählen',
            ],
        ],
    ],
    'time' => [
        'resource' => [
            'group' => 'Aufträge',  // Navigation group label
            'name' => 'Zeiteintrag',      // Singular name for the resource
            'name_plural' => 'Zeiteinträge', // Plural name for the resource
        ],
        'stats' => [
            'total_time_raw' => 'Gesamtzeit (Roh)',
            'total_time_raw_description' => 'Summe aller erfassten Zeiten ohne Rundung.',
            'total_time_rounded' => 'Gesamtzeit (Gerundet)',
            'total_time_rounded_description' => 'Summe aller erfassten Zeiten mit kaufmännischer Rundung (≥30 min aufgerundet).',
            'special_time' => 'Sonderzeit',
            'special_time_description' => 'Summe aller als "besonders" markierten Zeiten.',
            'entries_count' => 'Anzahl der Einträge',
            'entries_count_description' => 'Gesamtzahl der Zeiteinträge.',
            'unbilled_time' => 'Nicht verrechnete Zeit',
            'unbilled_time_description' => 'Gesamtzeit ohne Weiterverrechnung',
            'avg_time_per_contract' => 'Ø Zeit je Auftrag',
            'avg_time_per_contract_description' => 'Durchschnittliche Zeit pro Auftrag',
        ],
        'form' => [
            'general' => 'Allgemein',
            'field_date' => 'Datum*',
            'field_description' => 'Beschreibung',

            'time' => 'Zeit',
            'field_total_hours_worked' => 'Gesamtarbeitsstunden*',
            'field_total_minutes_worked' => 'Gesamtminuten',

            'contract' => 'Auftrag',
            'field_contract_label' => 'Auftrag*',

            'specification' => 'Spezifikation',
            'field_is_special' => 'Sonderzeit',

            'create' => 'Erstellen',
            'create_and_add_another' => 'Erstellen & weiteren Eintrag hinzufügen',
        ],
        'table' => [
            'billed' => 'Verrechnet',
            'date' => 'Datum',
            'description' => 'Beschreibung',
            'total_hours' => 'Gesamtstunden',
            'total_minutes' => 'Gesamtminuten',
            'is_special' => 'Sonderzeit',
        ],
        'filters' => [
            'billed' => 'Verrechnet',
            'not_billed' => 'Nicht Verrechnet',
            'contract_classification_user' => 'Benutzer',
            'contract_classification_contract' => 'Auftrag',
            'date_from' => 'Von',
            'date_until' => 'Bis',
        ],
        'bulk_actions' => [
            'select_all' => 'Alle Einträge für Stapelverarbeitung auswählen',
            'deselect_all' => 'Alle Einträge für Stapelverarbeitung abwählen',
            'mark_as_billed' => [
                'label' => 'Als abgerechnet markieren',
                'description' => 'Ausgewählte Einträge als abgerechnet setzen',
            ],
            'mark_as_not_billed' => [
                'label' => 'Als nicht abgerechnet markieren',
                'description' => 'Ausgewählte Einträge als nicht abgerechnet setzen',
            ],
        ],
    ],
    'bill' => [
        'resource' => [
            'name' => 'Rechnung',
            'group' => 'Aufträge',  // Navigation group label
            'name_plural' => 'Rechnungen',
        ],
        'bill_stats' => [
            'total_amount' => 'Gesamtbetrag',
            'total_amount_description' => 'Summe aller in Rechnung gestellten Beträge.',
            'total_unpaid_amount' => 'Gesamtbetrag Unbezahlt',
            'total_unpaid_amount_description' => 'Summe der noch nicht bezahlten Beträge.',
            'total_paid_amount' => 'Gesamtbetrag Bezahlt',
            'total_paid_amount_description' => 'Summe der bereits bezahlten Beträge.',
        ],
        'form' => [
            'field_flat_rate_amount'=>'Pauschalbetrag',
            'field_flat_rate_hours'=>'Stunden',
            'field_flat_rate_minutes'=>'Minuten',
            'field_is_flat_rate_helper'=>"Handelt es sich um einen Pauschalbetrag?",
            'field_is_flat_rate' => 'Pauschalbetrag',
            'section_general' => 'Allgemeine Informationen',
            'field_name' => 'Rechnungsname',
            'field_hourly_rate' => 'Stundensatz',
            'field_description' => 'Beschreibung',
            'field_due_to' => 'Fällig am',
            'field_created_on' => 'Erstellt am',
            'field_is_payed' => 'Bezahlt',

            'section_contract' => 'Auftrag',
            'field_contract' => 'Auftrag',

            'section_attachments' => 'Anhänge',
            'field_attachments' => 'Anhänge',
        ],
        'table' => [
            'user' => 'Benutzer',
            'contract' => 'Auftrag',
            'name' => 'Name',
            'description' => 'Beschreibung',
            'calculated_total' => 'Gesamt (€)',
            'is_payed' => 'Bezahlt',
        ],
        'filters' => [
            'payed' => 'Bezahlt',
            'not_payed' => 'Nicht Bezahlt',
            'flat_rate' => 'Pauschalbetrag',

            'user' => [
                'label' => 'Benutzer',
                'placeholder' => 'Benutzer auswählen',
            ],

            'contract' => [
                'label' => 'Auftrag',
                'placeholder' => 'Auftrag auswählen',
            ],
            'due_to' => 'Fälligkeitsdatum',
            'due_from' => 'Fällig ab',
            'due_until' => 'Fällig bis',
            'created_on' => 'Erstellungsdatum',
            'created_from' => 'Erstellt ab',
            'created_until' => 'Erstellt bis',
        ],
    ],
    'credentials' => [
        'resource' => [
            'name' => 'Zugang',
            'group' => 'Allgemein',
            'name_plural' => 'Zugänge',
        ],
        'form' => [
            'section_general' => 'Allgemeine Informationen',
            'field_name' => 'Zugangsname',
            'field_email' => 'E-Mail',
            'field_password' => 'Passwort',
            'field_description' => 'Beschreibung',
            'field_attachments' => 'Anhänge',
        ],
        'table' => [
            'name' => 'Name',
            'email' => 'E-Mail',
            'description' => 'Beschreibung',
            'created_at' => 'Erstellt am',
        ],
        'filters' => [
            'email' => 'E-Mail-Domain',
            'name' => 'Name enthält',
        ],
    ],
    'general_todo' => [
        'resource' => [
            'name' => 'Allgemeines Todos',
            'group' => 'Allgemein',
            'name_plural' => 'Allgemeine Todos',
        ],
        'navigation' => [
            'group' => 'Allgemein',
            'label' => 'Aufgaben',
        ],
        'form' => [
            'name' => 'Name',
            'due_to' => 'Fällig am',
            'description' => 'Beschreibung',
            'is_done' => 'Erledigt',
            'priority' => 'Priorität',
            'attachments' => 'Anhänge',
            'general' => 'Allgemeine Informationen',
            'priority_options' => [
                'low' => 'Niedrig',
                'mid' => 'Mittel',
                'high' => 'Hoch',
            ],
        ],
        'table' => [
            'name' => 'Name',
            'user' => 'Benutzer',
            'due_to' => 'Fällig am',
            'priority' => 'Priorität',
            'is_done' => 'Erledigt',
        ],
        'filters' => [
            'priority' => 'Priorität',
            'is_done' => 'Erledigt-Status',
            'due_to' => 'Fälligkeitszeitraum',
            'due_from' => 'Von',
            'due_until' => 'Bis',
            'user' => 'Benutzer',
        ],
    ],
    'user_stats' => [
        'unpaid_bills' => 'Offene Rechnungen',
        'your_contracts' => 'Ihre Aufträge',
        'unpaid_bills_description' => 'Noch offen',
        'your_contracts_description' => 'Gesamtanzahl Ihrer Aufträge',
        'open_todos' => 'Offene Aufgaben',
        'open_todos_description' => 'Die Anzahl der offenen Aufgaben, die noch bearbeitet werden müssen',
    ],
    'general_overview' => [
        'todays_calls' => 'Anrufe von heute',
        'unpaid_amount' => 'Offener Betrag',
        'unbilled_time' => 'Unabgerechnete Zeit',
        'unbilled_time_description' => 'Gesamtarbeitszeit, die noch nicht verrechnet wurde.',
        'contracts_due_3_days' => 'Aufträge, die in 3 Tagen fällig sind',
        'todos_due_3_days' => 'Todos, die in 3 Tagen fällig sind',
        'general_todos_due_3_days' => 'Allgemeine Todos, die in 3 Tagen fällig sind',
        'todays_calls_description' => 'Gesamtanzahl der heute anstehenden Anrufe',
        'unpaid_amount_description' => 'Gesamtsumme aller offenen Rechnungen',
        'contracts_due_3_days_description' => 'Aufträge, die kurz vor dem Fälligkeitsdatum stehen',
        'todos_due_3_days_description' => 'Todos, die bald fällig werden',
        'general_todos_due_3_days_description' => 'Allgemeine Todos, die bald fällig werden',
    ],
    'contract_stats' => [
        'heading' => 'Auftragsstatistiken',
        'due_today' => 'Aufträge, die heute fällig sind',
        'due_in_3_days' => 'Aufträge, die in 3 Tagen fällig sind',
        'completed' => 'Abgeschlossene Aufträge',
        'due_today_desc' => 'Aufträge, die heute fällig sind',
        'due_in_3_days_desc' => 'Aufträge, die in den nächsten 3 Tagen fällig sind',
        'completed_desc' => 'Aufträge, die als abgeschlossen markiert sind',
    ],
];
