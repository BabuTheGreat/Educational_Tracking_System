# Educational_Tracking_System
Developed and implemented a comprehensive database system to track personnel health statuses during the COVID-19 pandemic, providing vital insights for monitoring and managing health risks.

Implementation included a GUI to view and do CRUD operations. Database incorporated many constraints and requirements to resolve business problems. 

Examples of such constraints are: 

- Conflicting schedules
- An employee must be vaccinated for at least 6 months by the time of his scheduled date.
- Once a teacher gets infected with COVID-19, their schedule must be cleared within the next two weeks and an email must be sent to the principal of the school to inform others of the infection.
- Etc.

The system included an automated procedure to send an email to every employee about their respective schedule for the upcoming week.

NOTE: The system doesn't send an email, however, a dedicated relation/table was created to store every email that was "sent".
