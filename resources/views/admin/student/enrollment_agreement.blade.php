<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        *{
            break-inside: avoid;    
        }
    </style>
</head>

<body class="bg-gray-100 p-8" id="boxes">
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-lg">
        <div class="flex justify-between items-center mb-2">
            <div>
                <img alt="Academy UK logo" class="h-20 w-20"
                    src="data:image/png;base64,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">
            </div>
            <div class="text-center">
                <h1 class="text-xl font-bold">Academy of E learning, Britain</h1>
                <p class="text-sm">الاكاديمية البريطانية للتعليم الالكتروني</p>
            </div>
            <div>
                <img alt="ASC logo" class="h-16 w-16"
                    src="data:image/jpeg;base64,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" />
            </div>
        </div>
        <div class="text-center mb-2">
            <p>Address: {{ $topbarSetting->address }}</p>
            <p>Phone: {{ $topbarSetting->phone }}</p>
            <p>E-mail: {{ $topbarSetting->email }}</p>
        </div>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-center text-2xl font-bold mb-2">
                ENROLLMENT AGREEMENT
            </h1>
            <p class="mb-2">
                The Enrollment Agreement is a written contract signed between a student and the Academy of E-Learning
                Britain concerning an educational
                program in agreement with International American University. The agreement specifies all costs the
                student named below must pay to enroll
                in the specific educational program for the specified study program. Costs for the program may change if
                the specifics of the educational
                program changes (i.e. student takes more units/courses than what was originally outlined in the original
                Enrollment Agreement). A copy of
                the completed enrollment agreement shall be given to the student upon enrollment.
            </p>
            <div class="bg-black text-white p-2 mb-2">
                <h3 class="font-bold">
                    CLASS LOCATION
                </h3>
            </div>
            <p class="mb-2">
                For hybrid instruction, location of instruction shall take place at the designated main, branch or
                satellite campus.
                For online students, instruction shall take place in
                <span class="text-red-500">
                    LA online
                </span>
                . IAU's online instruction is offered in real time and shall transmit the first lesson
                and any materials to any student within seven days after the institution accepts the student for
                admission. IAU shall transmit all the lessons
                and other materials to the student if the student: (A) has fully paid for the educational program; and
                after having received the first lesson
                and initial materials, requests in writing that all the material be sent. If IAU transmits the balance
                of the material as the student requests, IAU
                shall remain obligated to provide the other educational services it agreed to provide, such as responses
                to student inquiries, student and
                faculty interaction, and evaluation and comment on lessons submitted by the student but shall not be
                obligated to pay any refund after all
                the lessons and material are transmitted.
            </p>
            <div class="bg-black text-white p-2 mb-2">
                <h3 class="font-bold">
                    STUDENT INFORMATION
                </h3>
            </div>
            <p class="mb-2">
                ({{ $student->first_name . ' ' . $student->father_name . ' ' . $student->last_name }} ,
                {{ $student->dob }}),
                <br />
                Address: {{ $student->permanent_address }}, {{ $student->permanent_village }},
                {{ $student->permanent_district }}
                <br />
                Email: {{ $student->email }}
            </p>
            <br><br><br><br><br><br><br><br><br>
            <div class="bg-black text-white p-2 mb-2">
                <h3 class="font-bold">
                    TUITION FEES
                </h3>
            </div>
            <p class="mb-2">
                ({{ $student->program->title }})
                <br />
                ({{ $student->program->faculty->title }}, International American University)
            </p>
            <table class="w-full border-collapse border border-black mb-2">
                <thead>
                    <tr>
                        <th class="border border-black p-2">
                            Fees Type
                        </th>
                        <th class="border border-black p-2">
                            Amount ($)
                        </th>
                        <th class="border border-black p-2">
                            Payment Due
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-2">
                            Admission Fees
                        </td>
                        <td class="border border-black p-2">
                            125
                        </td>
                        <td class="border border-black p-2">
                            Once at the beginning of first study session
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2">
                            Program basic tuition Fees
                        </td>
                        <td class="border border-black p-2">
                            6478
                        </td>
                        <td class="border border-black p-2">
                            In installments each study session
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2">
                            Credit Transfer Fees
                        </td>
                        <td class="border border-black p-2">
                            300
                        </td>
                        <td class="border border-black p-2">
                            If applicable
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2">
                            English Proficiency
                        </td>
                        <td class="border border-black p-2">
                            300
                        </td>
                        <td class="border border-black p-2">
                            If applicable
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2">
                            Graduation Fees
                        </td>
                        <td class="border border-black p-2">
                            900
                        </td>
                        <td class="border border-black p-2">
                            At the end of study completion
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="bg-black text-white p-2 mb-2">
                <h3 class="font-bold">
                    REGISTRATION
                </h3>
            </div>
            <p class="mb-2">
                IAU operates on a trimester academic calendar, which is comprised of three (3) academic terms (Spring,
                Summer,
                &amp; Fall). For each academic term, a student must register through the university's registration
                process. At the time
                of registration, the student will work with an academic staff to select the course(s) in which the
                student wishes to
                enroll for the academic term. The student will register for classes for the entire academic term.
                Depending on the
                student's Enrollment Status of full-time or part-time, the student will register for 1 to 4 courses per
                academic term.
                See explanation of Enrollment Status below.
            </p>
            <p class="mb-2">
                Spring (Jan-Apr)
                <br />
                • Session 1 (Jan-Feb)
                <br />
                • Session 2 (Mar-Apr)
            </p>
            <p class="mb-2">
                Summer (May-Aug)
                <br />
                • Session 1 (May-Jun)
                <br />
                • Session 2 (Jul-Aug)
            </p>
            <p class="mb-2">
                Fall (Sep-Dec)
                <br />
                • Session 1 (Sep-Oct)
                <br />
                • Session 2 (Nov-Dec)
            </p>
            <div class="bg-black text-white p-2 mb-2">
                <h3 class="font-bold">
                    FULL-TIME ENROLLMENT
                </h3>
            </div>
            <p>
                Full-time matriculated students are required to enroll full-time for each mandatory Spring and Fall
                trimester. F-1
                students are required to enroll full-time in the summer trimester if it is the initial or final
                enrollment term of their
                program. Full-time enrollment is defined as:
            </p>
        </div>
        <div class="mb-2">
            <ul class="list-disc pl-5">
                <li>
                    Undergraduate: 12 units per mandatory trimester.
                </li>
                <li>
                    Graduate: 9 units per mandatory trimester.
                </li>
            </ul>
        </div>
        <div class="bg-black text-white font-bold p-2 mb-2">
            PART-TIME ENROLLMENT
        </div>
        <div class="mb-2">
            <p>
                Part-time matriculated and non-matriculated students are required to enroll 6 units per trimester,
                including summer trimester.
            </p>
        </div>
        <br><br>
        <div class="bg-black text-white font-bold p-2 mb-2">
            POLICIES AND PROCEDURE FOR WITHDRAWAL AND REFUND
        </div>
        <div class="mb-2">
            <p>
                Application and admission fees are not refundable. Semester fees can be refundable completely or
                partially according to the conditions below. To withdraw or cancel the contract, fill in the form and
                submit it from your email to ({{ $topbarSetting->email }}).
            </p>
            <ul class="list-disc pl-5">
                <li>
                    Within the first week of session 100%
                </li>
                <li>
                    Within the 2nd week of the session 87.5%
                </li>
                <li>
                    within the 3rd week of the session 75%
                </li>
                <li>
                    within the 4th week of the session 62%
                </li>
                <li>
                    within the 5th week of the session 50%
                </li>
                <li>
                    later than the 5th week of the session 0%
                </li>
            </ul>
        </div>
        <div class="bg-black text-white font-bold p-2 mb-2">
            PROGRAM TUITION FEES INSTALLMENTS
        </div>
        <div class="mb-2">
            <p>
                Program basic tuition fees should be paid in instalments according to the table below.
            </p>
            <table class="w-full border-collapse border border-black">
                <thead>
                    <tr>
                        <th class="border border-black p-2">
                            Fees Type
                        </th>
                        <th class="border border-black p-2">
                            Paid Amount ($)
                        </th>
                        <th class="border border-black p-2">
                            Payment Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->installments as $installment)
                        <tr>
                            <td class="border border-black p-2">
                                {{ $installment->title }}
                            </td>
                            <td class="border border-black p-2">
                                {{ $installment->amount }}
                            </td>
                            <td class="border border-black p-2">
                                {{ $installment->payment_date }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <img src="{{ asset('dashboard/images/bank_info.png') }}" alt="bank info" width="130%">
        </div>
        <div class="bg-black text-white font-bold p-2 mb-2">
            CERTIFICATION
        </div>
        <div class="mb-2">
            <p>
                ________ (Initial) I understand that this enrollment agreement must be signed no later than (date).
                Otherwise, the enrollment agreement shall not be effective. ________ (Initial)
            </p>
            <p>
                ________ (Initial) I understand that this is a legally binding contract.
            </p>
            <p>
                My signature below certifies that I have read, understood, and agreed to my rights and responsibilities,
                and that the institution's cancellation and refund policies have been clearly explained to me.
            </p>
            <p class="font-bold">
                AEB Representative
            </p>
            <div class="flex justify-between mt-4">
                <div>
                    <p>
                        Signature
                    </p>
                    <p class="border-t border-black w-48">
                    </p>
                </div>
                <div>
                    <p>
                        Date
                    </p>
                    <p class="border-t border-black w-48">
                    </p>
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <div>
                    <p>
                        Signature
                    </p>
                    <p class="border-t border-black w-48">
                    </p>
                </div>
                <div>
                    <p>
                        Date
                    </p>
                    <p class="border-t border-black w-48">
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function closeScript() {
            setTimeout(function() {
                window.open(window.location, '_self').close();
            }, 1000);
        }

        $(window).on('load', function() {
            var element = document.getElementById('boxes');
            var opt = {
                filename: 'Enrollment-Agreement.pdf',
                image: {
                    type: 'jpeg', // Changed to jpeg
                    quality: 0.75 // Reduced quality
                },
                html2canvas: {
                    scale: 2, // Reduced scale
                    dpi: 96, // Reduced dpi
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4'
                }
            };
            html2pdf().set(opt).from(element).save().then(closeScript);
        });
    </script>
</body>

</html>
