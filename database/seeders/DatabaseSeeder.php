<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\School;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            SchoolSeeder::class,
            PurchaseSeeder::class,
            ProductPlanSeeder::class,
            AdminUserSeeder::class,
            SuperAdminAndSchoolSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AdmissionEnquirySeeder::class,
            VisitorSeeder::class,
            CallLogSeeder::class,
            PostalDispatchSeeder::class,
            PostalReceiveSeeder::class,
            ComplaintBoxSeeder::class,
            VisitorsPurposeSeeder::class,
            StudentDetailsSeeder::class,
            StudentSeeder::class,
            SchoolClassSeeder::class,
            SectionSeeder::class,
            ClassSectionSeeder::class,
            AttendanceSeeder::class,
            StudentResultsSeeder::class,
            SubjectsTableSeeder::class,
            StudentFeesSeeder::class,
            StudentPromotionsSeeder::class,
            StudentsHealthSeeder::class,
            StudentDocumentsSeeder::class,
            TransportRoutesSeeder::class,
            TransportVehiclesSeeder::class,
            StudentTransportSeeder::class,
            StudentCommunicationSeeder::class,
            StudentPortalAccessSeeder::class,
            HostelSeeder::class,
            ParentDetailsSeeder::class,
            ParentPortalAccessSeeder::class,
            BookSeeder::class,
            BookCategorySeeder::class,
            BookIssueSeeder::class,
            BookReturnSeeder::class,
            LibraryMemberSeeder::class,
            AcademicSubjectSeeder::class,
            AcademicSyllabusSeeder::class,
            AcademicLessonPlanSeeder::class,
            ResourceBookingSeeder::class,
            AcademicCalendarSeeder::class,
            AcademicReportSeeder::class,
            DocumentIdCardSeeder::class,
            DocumentTransferCertificateSeeder::class,
            DocumentBonafideCertificateSeeder::class,
            DocumentLeavingCertificateSeeder::class,
            DocumentMarksheetSeeder::class,
            DocumentExperienceCertificateSeeder::class,
            DocumentStudyCertificateSeeder::class,
            DocumentConductCertificateSeeder::class,
            DocumentEmployeeConductCertificateSeeder::class,
            ExamSeeder::class,
            ExamScheduleSeeder::class,
            ExamGradeSeeder::class,
            ExamMarkSeeder::class,
            ExamSmsSeeder::class,
            ExamSmsRecipientSeeder::class,
            ExamTabulationSeeder::class,
            ExamAttendanceSeeder::class,
            ExamProgressCardSeeder::class,
            QuestionCategorySeeder::class,
            QuestionSeeder::class,
            AiQuestionGenerationSeeder::class,
            QuestionPaperSeeder::class,
            ResultAnnouncementsSeeder::class,
            ResultPublicationsSeeder::class,
            ResultNotificationsSeeder::class,
            ResultStatisticsSeeder::class,
            ScholarshipSeeder::class,
            FeeStructureSeeder::class,
            StaffSeeder::class,
            PayrollSeeder::class,
            LeaveManagementSeeder::class,
            // Noticeboard Module Seeders
            DepartmentSeeder::class,
            NoticeboardTagSeeder::class,
            NoticeboardSeeder::class,
            NoticeboardViewSeeder::class,
            NoticeboardCommentSeeder::class,
            NoticeboardLikeSeeder::class,
            
            // Message System Seeders
            MessageSystemSeeder::class,
            
            // Email Templates Seeder
            EmailTemplateSeeder::class,
            
            // Newsletter Module Seeders
            NewsletterTemplateSeeder::class,
            NewsletterSubscriberSeeder::class,
            NewsletterSeeder::class,
            
            // Transport Module Seeders
            TransportRouteSeeder::class,
            TransportAssignmentSeeder::class,
            TransportDriverSeeder::class,
            TransportTrackingSeeder::class,
            
            // Hostel Module Seeders
            HostelCategorySeeder::class,
            
            // Accountant Details Seeder
            AccountantDetailsSeeder::class,
            
            // Payment Gateway Seeder
            PaymentGatewaySeeder::class,
            SuppliersTableSeeder::class,
            PurchasesTableSeeder::class,
            CanteenItemSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}