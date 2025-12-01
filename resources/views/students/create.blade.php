<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Student ID & Type -->
                        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700">Student ID <span class="text-red-500">*</span></label>
                                <input type="text" name="student_id" value="{{ old('student_id') }}" class="w-full border rounded px-3 py-2 @error('student_id') border-red-500 @enderror" required>
                                @error('student_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700">Student Type</label>
                                <select name="student_type" class="w-full border rounded px-3 py-2">
                                    <option value="">Select Type</option>
                                    <option value="Continuing" {{ old('student_type') == 'Continuing' ? 'selected' : '' }}>Continuing</option>
                                    <option value="New/Returner" {{ old('student_type') == 'New/Returner' ? 'selected' : '' }}>New/Returner</option>
                                    <option value="Candidate for graduation" {{ old('student_type') == 'Candidate for graduation' ? 'selected' : '' }}>Candidate for graduation</option>
                                </select>
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="border-b-2 border-brand-light pb-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-medium" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border rounded px-3 py-2 @error('last_name') border-red-500 @enderror" required>
                                    @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border rounded px-3 py-2 @error('first_name') border-red-500 @enderror" required>
                                    @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Maiden Name (F)</label>
                                    <input type="text" name="maiden_name" value="{{ old('maiden_name') }}" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Date of Birth <span class="text-red-500">*</span></label>
                                    <input type="date" name="birthdate" value="{{ old('birthdate') }}" class="w-full border rounded px-3 py-2 @error('birthdate') border-red-500 @enderror" required>
                                    @error('birthdate')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Place of Birth</label>
                                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" class="w-full border rounded px-3 py-2" placeholder="City/Province">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Gender <span class="text-red-500">*</span></label>
                                    <select name="gender" class="w-full border rounded px-3 py-2" required>
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Citizenship</label>
                                    <input type="text" name="citizenship" value="{{ old('citizenship') }}" class="w-full border rounded px-3 py-2" placeholder="Filipino">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Ethnicity/Tribal Affiliation</label>
                                    <input type="text" name="ethnicity_tribal_affiliation" value="{{ old('ethnicity_tribal_affiliation') }}" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-b-2 border-brand-light pb-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-medium" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.8c.163.5.35 1.404.353 1.405.012.092.218.1.35.004l3.454-3.454a1 1 0 011.414 0l3.447 3.447a1 1 0 01.003 1.352c-.101.129-1.016.335-1.508.348.016.032.477 1.332.477 2.052V10a2 2 0 01-2 2h-.141l-.964 2.89a1 1 0 01-.940.66h-.172a1 1 0 01-.940-.66l-.964-2.89h-.141a2 2 0 01-2-2V6.859c0-.72.462-2.02.477-2.052-.492-.013-1.407-.219-1.508-.348a1 1 0 01.003-1.352l3.447-3.447a1 1 0 011.414 0l3.454 3.454c.132.096.338.088.35-.004.003-.001.19-.905.353-1.405a1 1 0 01.986-.8h2.153a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V3z"></path></svg>
                                Contact Information
                            </h3>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Contact Number</label>
                                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="w-full border rounded px-3 py-2" placeholder="09xx-xxx-xxxx">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Email Address</label>
                                    <input type="email" name="email_address" value="{{ old('email_address') }}" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Permanent/Home Address</label>
                                <textarea name="home_address" rows="2" class="w-full border rounded px-3 py-2" placeholder="Complete address">{{ old('home_address') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Address while studying at BSU</label>
                                <textarea name="address_while_studying" rows="2" class="w-full border rounded px-3 py-2">{{ old('address_while_studying') }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Mother's Name</label>
                                    <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Mother's Contact Number</label>
                                    <input type="text" name="mother_contact_number" value="{{ old('mother_contact_number') }}" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Father's Contact Number</label>
                                <input type="text" name="father_contact_number" value="{{ old('father_contact_number') }}" class="w-full border rounded px-3 py-2">
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Emergency Contact Person</label>
                                    <input type="text" name="emergency_contact_person" value="{{ old('emergency_contact_person') }}" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Relationship</label>
                                    <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium mb-2">Emergency Contact Number</label>
                                <input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="border-b-2 border-brand-light pb-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-medium" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                Academic Information
                            </h3>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Program <span class="text-red-500">*</span></label>
                                    <select name="program_id" class="w-full border rounded px-3 py-2 @error('program_id') border-red-500 @enderror" required>
                                        <option value="">Select Program</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                                {{ $program->code }} - {{ $program->name }}
                                            </option>
                                        @endforeach
                                    </select>
@error('program_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Department</label>
                                    <input type="text" id="programDepartment" class="w-full border rounded px-3 py-2 bg-gray-100" value="" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Degree</label>
                                    <input type="text" name="degree" value="{{ old('degree') }}" class="w-full border rounded px-3 py-2" placeholder="e.g., Bachelor">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Year Level <span class="text-red-500">*</span></label>
                                    <select name="year_level" class="w-full border rounded px-3 py-2" required>
                                        <option value="">Select Year</option>
                                        <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Major</label>
                                    <input type="text" name="major" value="{{ old('major') }}" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Section</label>
                                    <input type="text" name="section" value="{{ old('section') }}" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Attendance Type</label>
                                    <select name="attendance_type" class="w-full border rounded px-3 py-2">
                                        <option value="">Select Type</option>
                                        <option value="regular" {{ old('attendance_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="irregular" {{ old('attendance_type') == 'irregular' ? 'selected' : '' }}>Irregular</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Curriculum Used</label>
                                    <input type="text" name="curriculum_used" value="{{ old('curriculum_used') }}" class="w-full border rounded px-3 py-2" placeholder="e.g., CHED2015">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full border rounded px-3 py-2" required>
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="On Leave" {{ old('status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                    <option value="Dropped" {{ old('status') == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                                    <option value="Graduated" {{ old('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                </select>
                            </div>
</div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const programSelect = document.querySelector('select[name="program_id"]');
                                const deptInput = document.getElementById('programDepartment');
                                const programDeptMap = {
                                    @foreach($programs as $p)
                                        {{ $p->id }}: @json($p->department?->name ?? ''),
                                    @endforeach
                                };
                                function updateDept() {
                                    const id = programSelect.value;
                                    deptInput.value = programDeptMap[id] || '';
                                }
                                programSelect.addEventListener('change', updateDept);
                                updateDept();
                            });
                        </script>

                        <!-- Registration Information -->
                        <div class="border-b-2 border-brand-light pb-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-medium" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1V7z"></path></svg>
                                Registration Information
                            </h3>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Total Units Enrolled</label>
                                    <input type="number" name="total_units_enrolled" value="{{ old('total_units_enrolled') }}" class="w-full border rounded px-3 py-2" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Free Higher Education Benefit</label>
                                    <select name="free_higher_education_benefit" class="w-full border rounded px-3 py-2">
                                        <option value="">Select Option</option>
                                        <option value="yes" {{ old('free_higher_education_benefit') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="yes_with_contribution" {{ old('free_higher_education_benefit') == 'yes_with_contribution' ? 'selected' : '' }}>Yes, with voluntary contribution</option>
                                        <option value="no_optout" {{ old('free_higher_education_benefit') == 'no_optout' ? 'selected' : '' }}>No, I will pay tuition fee (Opt-out)</option>
                                        <option value="not_qualified" {{ old('free_higher_education_benefit') == 'not_qualified' ? 'selected' : '' }}>Not Qualified</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-medium" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                                Photo
                            </h3>
                            <input type="file" name="photo" accept="image/*" class="w-full border rounded px-3 py-2 @error('photo') border-red-500 @enderror">
                            @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Max 2MB, JPG/PNG</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                                Save Student
                            </button>
                            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
