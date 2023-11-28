<div style="text-align: center;">
    <img src="http://maydayphone.com/images/mayday-phone-logo.png" alt="Mon Logo" width='200'>
    </div>
    <br>

    <p style="text-align: left; color:black">
        Cher <strong>Administrateur</strong>,
    </p> <br>
        <p style="text-align: justify;color:black">
            Vous aviez un nouvel message de contact de la part de <strong>{{ $contact['NOM'] }}</strong> <strong>{{ $contact['PRENOM'] }}</strong>.
            <br>
            Ses informations de contact sont :
            EMAIL : <strong>{{ $contact['E-MAIL'] }} </strong><br>
            TELEPHONE : <strong>{{ $contact['TELEPHONE'] }} </strong> <br>

            Il vous as addressé le message suivant : <br>
            <strong>{{ $contact['MESSAGE'] }} </strong> <br>

            NUMERO DE FACTURE :  <strong>{{ $contact['NUMERO_DE_FACTURE'] }} </strong> <br>

        Merci de faire confiance à notre service. Si vous avez des questions ou avez besoin d'aide, n'hésitez pas à nous contacter.
        </p>

        <p style="text-align: left;color:black">
            Cordialement,
            <strong>MAYDAY PHONE</strong>
        </p>

