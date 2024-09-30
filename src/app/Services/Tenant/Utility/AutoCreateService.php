<?php


namespace App\Services\Tenant\Utility;


use App\Helpers\Core\Traits\InstanceCreator;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\Designation;
use App\Models\Tenant\Employee\EmploymentStatus;
use App\Models\Tenant\Leave\LeaveType;
use App\Models\Tenant\Leave\LeavePeriod;
use App\Models\Tenant\WorkingShift\WorkingShift;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AutoCreateService extends TenantService
{
    use InstanceCreator;

    public function trigger(array $attributes = []): void
    {
        if (array_key_exists('tenant_id', $attributes)) {
            $this->createEmploymentStatus($attributes['tenant_id']);
            $this->createLeavePeriod($attributes['tenant_id']);
            $this->createLeaveCategory($attributes['tenant_id']);
            $this->createWorkingShift($attributes['tenant_id']);
        }

        if (array_key_exists('manager_id', $attributes)) {
            /** @var Department $department */
            $department = $this->createDepartment($attributes);

            unset($attributes['manager_id']);

            $this->createDesignation($department, $attributes);
        }
    }



    public function createLeaveCategory($tenant_id = 1): void
    {
        $categories = collect([
            ['name' => 'Paid Casual', 'type' => 'paid', 'amount' => 5.50],
            ['name' => 'Paid Sick', 'type' => 'paid', 'amount' => 5.50],
            ['name' => 'Unpaid Casual', 'type' =>  'unpaid', 'amount' => 5.50],
            ['name' => 'Unpaid Sick', 'type' =>  'unpaid', 'amount' => 5.50],
        ])->map(fn ($v) => [
            'name' => $v['name'],
            'alias' => Str::slug($v['name']),
            'type' => $v['type'],
            'amount' => $v['amount'],
            'tenant_id' => $tenant_id
        ]);

        LeaveType::query()->insert(
            $categories->toArray()
        );
    }


    public function createLeavePeriod(int $tenant_id = 1): void
    {
        LeavePeriod::query()->create([
            'start_date' => now()->firstOfYear(),
            'end_date' => now()->addYear(),
            'tenant_id' => $tenant_id
        ]);
    }

    public function createDepartment(array $attributes = []): void
    {
        $status_id = resolve(StatusRepository::class)->departmentActive();
        $tenant_id = 1;
        $departments = [
            [
                "name" => "ACADEMIC PLANNING UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "BURSARY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "DIRECTORATE OF CORPORATE AFFAIRS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "DIRECTORATE OF GENERAL SERVICES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "FIRE SERVICES UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "HEALTH SERVICES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "INTERNAL AUDIT UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "LEGAL SERVICES UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHYSICAL PLANNING UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PROCUREMENT UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "QUALITY ASSURANCE BUREAU",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "REGISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SECURITY DIVISION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SIWES UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNIVERSITY GUEST HOUSE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNIVERSITY LODGES UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VICE-CHANCELLOR'S LODGE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WORKS: CIVIL/BUILDING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WORKS: ELECTRICAL/TELECOM",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WORKS: ESTATE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WORKS: MECHANICAL",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WORKS: PARKS & GARDENS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WORKS: WORKS/MAINT. OFFICE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "AGRIC. ECONOMICS & FARM MANAGEMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "AGRIC. EXTENSION & RURAL DEVELOPMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "AGRONOMY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ANIMAL PRODUCTION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "APIARY UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "AQUACULTURE  & FISHERIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CROP PROTECTION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "FOREST RESOURCES MANAGEMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "HOME ECONOMICS & FOOD SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNILORIN SUGAR RESEARCH INSTITUTE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ARABIC",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ENGLISH",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "FRENCH",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "HISTORY & INTERNATIONAL STUDIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "LINGUISTICS & NIGERIAN LANGUAGES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "RELIGIONS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "THE PERFORMING ARTS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CHEMICAL PATHOLOGY & IMMUNOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "HAEMATOLOGY & BLOOD TRANSFUSION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MEDICAL LABORATORY SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MEDICAL MICROBIOLOGY & PARASITOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MEDICAL RADIOGRAPHY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PATHOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHARMACOLOGY & THERAPEUTICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHYSIOTHERAPY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ANATOMY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MEDICAL BIOCHEMISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHYSIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRAL RESEARCH LABORATORIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR ARCHIVES & DOCUMENTATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR COUNSELLING & HUMAN DEVELOPMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR ILORIN STUDIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR INTERNATIONAL EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR OPEN & DISTANCE LEARNING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR PEACE & STRATEGIC STUDIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "INSTITUTE OF MEDICAL RESEARCH & TRAINING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "TECHNICAL & ENTREPRENEURSHIP CENTRE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ANAESTHESIA",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "BEHAVIOURAL SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "DENTISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "EPIDEMIOLOGY & COMMUNITY HEALTH",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "FAMILY MEDICINE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MEDICINE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "NURSING SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "OBSTETRICS & GYNAECOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "OPHTHALMOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "OTORHINOLARYNGOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PAEDIATRICS & CHILD HEALTH",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "RADIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SURGERY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COLLEGE OF HEALTH SCIENCES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COLLEGE OF HEALTH SCIENCES-WORKS UNIT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COMPUTER SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "INFORMATION & COMMUNICATION SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "LIBRARY & INFORMATION SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MASS COMMUNICATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "TELECOMMUNICATION SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COMSIT DIRECTORATE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COMPUTER BASE TEST",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "NETWORK OPERATING CENTRE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PORTAL SERVICES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PROCUREMENT & MAINTENANCE SUPORT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "TRAINING RESEARCH & DEVELOPMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WEB SUPPORT SERVICES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ADULT & PRIMARY EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ARTS EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRE FOR SUPPORTIVE SERVICES FOR THE DEAF",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COUNSELLOR EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "EDUCATIONAL MANAGEMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "EDUCATIONAL TECHNOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "HEALTH PROMOTION & ENVIRONMENTAL HEALTH EDU",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "HUMAN KINETICS & HEALTH EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "INSTITUTE OF EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SCIENCE EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SOCIAL SCIENCE EDUCATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "AGRICULTURAL & BIOSYSTEM ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "BIOMEDICAL ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRAL WORKSHOP:ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CHEMICAL ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CIVIL ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "COMPUTER ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ELECTRICAL & ELECTRONICS ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "EQUIPMENT MAINT. CENTRE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "FOOD & BIOPROCESS ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MATERIALS & METALLURGICAL ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MECHANICAL ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "WATER RESOURCES & ENVIRONMENTAL ENGINEERING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ARCHITECTURE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ESTATE MANAGEMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "QUANTITY SURVEYING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SURVEYING & GEO-INFORMATICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "URBAN & REGIONAL PLANNING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "BUSINESS LAW",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ISLAMIC LAW",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "JURISPRUDENCE & INTERNATIONAL LAW",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PRIVATE & PROPERTY LAW",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PUBLIC LAW",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "BIOCHEMISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MICROBIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "OPTOMETRY & VISION SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PLANT BIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNILORIN ZOO",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ZOOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ACCOUNTING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "BUSINESS ADMINISTRATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "FINANCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "INDUSTRIAL RELATIONS & PERSONNEL MANAGEMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MARKETING",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PUBLIC ADMINISTRATION",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "LIBRARY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNILORIN STAFF SCHOOL",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNILORIN STAFF SCHOOL (UPPER BASIC SCHOOL)",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CLINICAL PHARMACY & PHARMACY PRACTICE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHARMACEUTICAL & MEDICINAL CHEMISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHARMACEUTICAL MICROBIOLOGY & BIOTECHNOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHARMACEUTICS & INDUSTRIAL PHARMACY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHARMACOGNOSY & DRUG DEVELOPMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHARMACOLOGY & TOXICOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CHEMISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "GEOLOGY & MINERAL SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "GEOPHYSICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "INDUSTRIAL CHEMISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "MATHEMATICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PHYSICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "STATISTICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "UNILORIN PRESS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CRIMINOLOGY & SECURITY STUDIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "ECONOMICS",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "GEOGRAPHY & ENVIRONMENTAL MANAGEMENT",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "POLITICAL SCIENCE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "PSYCHOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SOCIAL WORK",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "SOCIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "CENTRAL RESEARCH LABORATORIES",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "TEACHING & RESEARCH FARM",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY ANATOMY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY MEDICINE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY MICROBIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY PARASITOLOGY & ENTOMOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY PATHOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY PHARMACOLOGY & TOXICOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY PHYSIOLOGY & BIOCHEMISTRY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY PUBLIC HEALTH & PREVENTIVE MEDICINE",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY SURGERY & RADIOLOGY",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY TEACHING HOSPITAL",    'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ],
            [
                "name" => "VETERINARY THERIOGENOLOGY & PRODUCTION",  'status_id' => $status_id,     'tenant_id' => $tenant_id,
            ]
        ];
        Department::query()->insert($departments);
        // return Department::query()->create(array_merge([
        //     'name' => 'Main Department',
        //     'status_id' => $status_id,     'tenant_id' => $tenant_id,
        // ], $attributes));
    }

    public function createEmploymentStatus($tenant_id = 1): void
    {
        $statuses = collect([
            ['name' => 'Probation', 'class' => 'warning', 'alias' => 'probation'],
            ['name' => 'Permanent', 'class' => 'primary', 'alias' => 'permanent'],
            ['name' => 'Terminated', 'class' => 'danger', 'alias' => 'terminated'],
        ])->map(fn ($v) => [
            'name' => $v['name'],
            'class' => $v['class'],
            'is_default' => 1,
            'tenant_id' => $tenant_id,
            'alias' => $v['alias']
        ]);

        EmploymentStatus::query()->insert(
            $statuses->toArray()
        );
    }

    public function createDesignation(Department $department, array $attributes = [])
    {
        $tenant_id = 1;
        $designations = [
            [
                "name" => "Higher Health Assistant - CONHESS 04",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Assistant - CONHESS 04",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Laboratory Supervisor - CONHESS 05",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Health Assistant - CONHESS 05",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Health Assistant - CONHESS 06",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Environmental Health Assistant - CONHESS 06",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Health Assistant - CONHESS 07",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Health Record Officer II - CONHESS 07",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Health Record Officer I - CONHESS 08",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Technician - CONHESS 08",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Medical Laboratory Technician - CONHESS 08",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Pharmacy Technician - CONHESS 08",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Animal Health Superintendent - CONHESS 09",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Pharmacy Technician - CONHESS 09",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Technologist - CONHESS 09",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Nursing Officer - CONHESS 09",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Technologist - CONHESS 09",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Laboratory Technician - CONHESS 11",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Nursing Officer - CONHESS 11",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Technologist - CONHESS 11",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Nursing Officer - CONHESS 12",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Pharmacy Technician - CONHESS 12",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Environmental Health Officer - CONHESS 13",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Nursing Officer - CONHESS 13",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Pharmacist - CONHESS 13",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Medical Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Lecturer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Registrar Grade I (VET)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Lecturer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Consultant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Medical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Reader",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Medical Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Consultant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Professor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Medical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Director (HEALTH SERVICES)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Patrolman II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Driver",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Head Livestock Attendant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Patrolman I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Groundsman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Clerical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Craftsman (ROAD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Head Guardsman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Laboratory Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Driver",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Patrolman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Supervisor Office Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Foreman (Laundry)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Patrol Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Caretaker",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Porter",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Steward",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Field Overseer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Head Driver",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Head Zoo Keeper",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Leading Fireman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Secretarial Assistant II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Clerical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Craftsman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Craftsman (Carpentry)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Craftsman (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Craftsman (Masson)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Craftsman (PLUMBING)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Craftsman (Refrigeration & Airconditioner)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Driver Mechanic II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Farm Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Library Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Supervisor Office Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Animal Health Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Clerical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Zoo Keeper",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Executive Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Executive Officer (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Farm Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Library Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Supervisor (Porter)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Catering Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman (Carpentry)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman (Gardening)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman (Operator)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman (Plumbing)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman (Sports)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman (TEC)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Foreman Operator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Laboratory Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Library Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Patrol Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Driver Mechanic I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Fireman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Catering Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Photographer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Security Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Clerical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Fireman",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Store Keeper",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Zoo Keeper",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Confidential Secretary II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Executive Officer (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Executive Officerr (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Farm Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Library Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Lodge Supervisor Porter",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Computer Operator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Foreman (Carpenter)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Foreman (CARPENTRY)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Foreman (Electrical)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Foreman (GARDENING)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Foreman (Mechanical)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Foreman (Welding)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Secretarial Assistant  II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Secretarial Assistant II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Grade IV",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Supervisor (Porter)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Transport Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Administrative Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Catering Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Computer Operator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Farm Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Livestock Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Confidential Secretary I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Gardening Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Estate Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Executive Officer (INF)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Library Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Lodge Supervisor (Porter)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Medical Laboratory Technician",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Technical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Technical Officer (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Technical Officer (MECHANICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Transport Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Higher Works Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Laboratory Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Security Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Secretarial Assistant I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Grade III",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Workshop Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Signer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Supervisor (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Supervisor (WELDER)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Technologist II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Zoo Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Administrative Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Accountant I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Accountant I (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Secretarial Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Counsellor I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Information Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Laboratory Technologist I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Library Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Maintenance Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Accountant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Animal Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Catering Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Coach",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Confidential Secretary",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Data Analyst",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Estate Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Excutive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Executive Officer (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Executive Officer (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Executive Officer (INF)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Executive Officer(GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Fire Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Gardening Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Labaratory Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Library Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Library Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Lodge Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Security Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Stores Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Special Grade II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Technical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Technical Officer (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Transport Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Turnstile Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Works Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Zoo Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Signer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "System Analyst I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Technologist I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Workshop Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Registrar",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Lecturer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Chief Security Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Operation Manager",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal  Medical Laboratory Technician",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Accountant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Agricultural Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Archives Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Catering Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Confidential Secretary I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Confidential Secretary II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Curator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Data Analyst II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Estate Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer II (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer II (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer II (INF)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Fire Superintendent II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Laboratory Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Library Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Procurement Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Store Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Stores Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Technical Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Transport Supervisor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Works Superintendent II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Accountant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Archivist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Audiologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Cartographer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Clinical Instructor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Coach",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Coordinator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Counsellor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Editor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Engineer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Engineer (MECHANICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Information Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Information Officer (MKT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Instructional Designer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Legal Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Maintenance Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Planning Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Programmer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Quantity Surveyor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Signer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior System Analyst",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Grade I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Special Grade I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Special Grade II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Works Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Zoo Suprintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Assistant Registrar",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Head Teacher",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Security Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Accountant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Accountant (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Apiary Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Architect",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Clinical Instructor (NURSING)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Coach",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Computer Engineer (NETWORK)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Confidential Secretary I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Coordinator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Counsellor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Curator I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Editor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Engineer (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Engineer (Electronics)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Engineer (MECHANICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Engineer (STRUCTURAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer I (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer I (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Executive Officer I (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Graphic Arts Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Information Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Legal Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Library Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Maintenance Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Planning Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Programmer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Quantity Surveyor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Signer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal System Analyst",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Technical Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Prinicipal Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Medical Officer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Grade II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Special Grade I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Special Grade II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher SpecialGrade II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior TeacherSpecial Grade II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief  Medical Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Confidential Secretary",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Estate Officer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Executive Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Executive Officer (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Pharmacist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Store Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Chief Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Head Teacher",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Laboratory Technician",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Pharmacy Techncian",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Teacher Special Grade I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Assistant Registrar",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Director (INF)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Director (PROTOCOL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Director (Security)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Head Teacher",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Accountant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Agricultural Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Apiary Superintendent",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Archivist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Cartographer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Catering Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Computer Electronics Engineer (NETWORK)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Confidential Secretary",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Coordinator",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Counsellor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Data Analyst",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Engineer (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Executive Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Executive Officer  (ACCT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Executive Officer (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Executive Officer (GD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Laboratory  Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Legal Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Librarian",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Library Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Maintenance Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Procurement Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Store Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief System Analyst",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Technical Officer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Chief Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Press Manager",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Assistant Head Teacher",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "SeniorPrincipal Assistant Registrar",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Special Head Teacher",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Registrar",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Bursar",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director  (CSSD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (CIVIL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (COMSIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (CORPORATE AFFAIRS)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (CSSD)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (ELECTRICAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (ESTATE)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (INF)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (LEGAL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (NETWORK)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (PPU)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (PROTOCOL)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (QUALITY ASSURANCE)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (SIWES)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Head Teacher",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Chief Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Chief Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Director (AUDIT)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Director (CORPORATE AFFAIRS)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Director (WORKS)",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Chief Laboratory Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Chief Technologist",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Reader",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Graduate Assistant",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Librarian",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Assistant Lecturer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Junior Research Fellow",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Librarian II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Lecturer II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Librarian I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Research Fellow II",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Lecturer I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Research fellow I",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Librarian",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Lecturer",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Principal Librarian",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Senior Research Fellow",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Reader",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Deputy Librarian",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Professor",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Vice-Chancellor - CONSOLIDATED",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Registrar - CONSOLIDATED",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Bursar - CONSOLIDATED",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ],
            [
                "name" => "Librarian - CONSOLIDATED",
                'tenant_id' => $tenant_id,
                "is_default" => true
            ]
        ];

        
        Designation::query()->insert($designations);
        // $department->designations()->create(array_merge([
        //     'name' => 'Director',
        //     'is_default' => true,
        // ], $attributes));
    }

    public function createWorkingShift(int $tenant_id = 1)
    {
        $workingShift = WorkingShift::query()->create([
            'name' => 'Regular work shift',
            'tenant_id' => $tenant_id,
            'is_default' => 1,
            'type' => 'regular'
        ]);

        $this->saveWorkingShiftDetails($workingShift);
    }

    public function saveWorkingShiftDetails(WorkingShift $workingShift, $start_time = '09:00:00', $end_time = '17:00:00')
    {
        $callback = fn ($day) => [
            'weekday' => $day,
            'is_weekend' => !!in_array($day, ['fri', 'sat']) ? 1 : 0,
            'start_at' => !!in_array($day, ['fri', 'sat']) ? null : $start_time,
            'end_at' => !!in_array($day, ['fri', 'sat']) ? null : $end_time,
            'working_shift_id' => $workingShift->id
        ];

        $workingShift->details()->insert(
            array_map($callback, config('settings.weekdays'))
        );
    }
}
