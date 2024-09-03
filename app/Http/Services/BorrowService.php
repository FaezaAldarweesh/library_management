<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;

class BorrowService
{
    use ApiResponseTrait;
    public function getAllBorrowRecords()
    {
        //عرض فقططط سجلات الاستعارة الخاصو بالمستخدم
        return BorrowRecord::where('user_id' , '=' , Auth::id())->get();
    }
    //===========================================================================================================================
    public function createBorrowRecord($data)
    {    
        // أولاً، إحضار الكتاب المراد استعارته باستخدام book_id
        $book = Book::findOrFail($data['book_id']);  

        // التحقق من حالة الكتاب المراد استعارته في حال كان متاح أم لا
        if ($book->status == 'available') {

            // في حال أن حالة الكتاب متاح ستتم عملية الاستعارة بنجاح
            // وتتغير حالة الكتاب من متاح إلى غير متاح
            $borrow_at = Carbon::parse($data['borrow_at']);
    
            $borrow = BorrowRecord::create([
                'book_id' => $data['book_id'],
                'user_id' => Auth::id(),
                'borrow_at' => $data['borrow_at'],
                'due_at' => $data['due_at'],
                'returned_at' => $borrow_at->clone()->addDays(14),
            ]);
    
            // تحديث حالة الكتاب إلى غير متاح
            $book->update(['status' => 'unavailable']);
    
            return $borrow;
        } else {
            //في حال كان الكتاب غير متاح سوف تعاد رسالة الخطأ التالية للمستخدم
            throw new \Exception( 'You cannot borrow this book because it is unavailable now');
        }
    }

    //===========================================================================================================================
    public function updateBorrowRecord($data, $id)
    {
        // إحضار سجل الاستعارة
        $borrowRecord = BorrowRecord::findOrFail($id);
    
        // أولاً، التحقق من حالة الاستعارة , حيث يستطيع المستخدم التعديل على سجل الاستعارة إّذا لم يتم استلام الكتاب من المكتبة
        if ($borrowRecord->status == 'It has not been borrowed yet') {
    
            // تخزين القيمة القديمة لـ book_id
            $book_id_old_value = $borrowRecord->book_id;
    
            // التحقق مما إذا كانت القيمة الجديدة لـ book_id تساوي القيمة القديمة
            // يجب التحقق أولا من القيمة الجديدة للكتاب , في حال عدل على الكتاب المراد استعارته يجب اعادة حالة الكتاب السابق لمتاح و نغيير حالة الكتاب الجديدإلى غير متاح
            if ($book_id_old_value != $data['book_id']) {
    
                // إحضار الكتاب الجديد المراد استعارته
                $book = Book::findOrFail($data['book_id']);
    
                // التحقق من حالة الكتاب الجديد ,, من الممكن أن يكون مستعار سابقا من قبل مستخدم أخر
                if ($book->status != 'available') {
                    // في حال كان الكتاب غير متاح، إعادة استثناء
                    throw new \Exception('You cannot borrow this book because it is unavailable now');
                }
    
                // تحديث حالة الكتاب القديم إلى متاح
                $oldBook = Book::findOrFail($book_id_old_value);
                $oldBook->update(['status' => 'available']);
                
                // تحديث حالة الكتاب الجديد إلى غير متاح
                $book->update(['status' => 'unavailable']);
            }
    
            // تحديث بيانات السجل الحالي
            $borrowRecord->update([
                'book_id' => $data['book_id'],
                'borrow_at' => $data['borrow_at'],
                'due_at' => $data['due_at'],
                'returned_at' => Carbon::parse($data['borrow_at'])->clone()->addDays(14),
            ]);
    
            return $borrowRecord;
        } else {
            // في حال كان الكتاب قد تم استلامه أو إرجاعه، إعادة استثناء لا يمكنك التعديل على سجل استعارة حالته منتهية أو الكتاب قد استعير من قبل المستخدم
            throw new \Exception('You cannot update the borrow record after the book has been taken or returned');
        }
    }
    //===========================================================================================================================
    public function showBorrowRecord($id){
        $borrowRecord = BorrowRecord::findOrFail($id);
        return $borrowRecord;
    }
    //===========================================================================================================================
    public function deleteBorrowRecord($id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);
        $borrowRecord->delete();
        return true;
    }




    //===========================================================================================================================
   //التوابع الخاصة بالأدمن فقطططط



    public function getAllBorrowRecordsForAdmin()
    {
        //إحضار جميع سجلات الإستعارة
        return BorrowRecord::all();
    }
    //===========================================================================================================================
    //فمت بإعادة صياغة التاابع لأن الأدمن يقوم بتسجيل الاستعارة أي لن تكون قيمة user_id = Auth::user()
    public function createBorrowRecordForAdmin($data)
    {
        // أولاً، إحضار الكتاب المراد استعارته باستخدام book_id
        $book = Book::findOrFail($data['book_id']);    
        // التحقق من حالة الكتاب المراد استعارته
        if ($book->status == 'available') {
            // في حال أن حالة الكتاب متاح ستتم عملية الاستعارة بنجاح
            // وتتغير حالة الكتاب من متاح إلى غير متاح
            $borrow_at = Carbon::parse($data['borrow_at']);
    
            $borrow = BorrowRecord::create([
                'book_id' => $data['book_id'],
                'user_id' => $data['user_id'],
                'borrow_at' => $data['borrow_at'],
                'due_at' => $data['due_at'],
                'returned_at' => $borrow_at->clone()->addDays(14),
            ]);
    
            // تحديث حالة الكتاب إلى غير متاح
            $book->update(['status' => 'unavailable']);
    
            return $borrow;
        } else {
            //في حال كان الكتاب غير متاح سوف تعاد رسالة الخطأ التالية للمستخدم
            throw new \Exception( 'You cannot borrow this book because it is unavailable now');
        }
    }
    //===========================================================================================================================
    public function updateBorrowRecordForAdmin($data, $id)
    {
        // إحضار سجل الاستعارة
        $borrowRecord = BorrowRecord::findOrFail($id);
    
        // أولاً، التحقق من حالة الاستعارة
        if ($borrowRecord->status == 'It has not been borrowed yet') {
    
            // تخزين القيمة القديمة لـ book_id
            $book_id_old_value = $borrowRecord->book_id;
    
            // التحقق مما إذا كانت القيمة الجديدة لـ book_id تساوي القيمة القديمة
            if ($book_id_old_value != $data['book_id']) {
    
                // إحضار الكتاب الجديد المراد استعارته
                $book = Book::findOrFail($data['book_id']);
    
                // التحقق من حالة الكتاب الجديد
                if ($book->status != 'available') {
                    // في حال كان الكتاب غير متاح، رمي استثناء
                    throw new \Exception('You cannot borrow this book because it is unavailable now');
                }
    
                // تحديث حالة الكتاب القديم إلى متاح
                $oldBook = Book::findOrFail($book_id_old_value);
                $oldBook->update(['status' => 'available']);
                
                // تحديث حالة الكتاب الجديد إلى غير متاح
                $book->update(['status' => 'unavailable']);
            }
    
            // تحديث بيانات السجل الحالي
            $borrowRecord->update([
                'book_id' => $data['book_id'],
                'user_id' => $data['user_id'],
                'borrow_at' => $data['borrow_at'],
                'due_at' => $data['due_at'],
                'returned_at' => Carbon::parse($data['borrow_at'])->clone()->addDays(14),
                'status' => $data['status'],
            ]);
    
            return $borrowRecord;
        } else {
            // في حال كان الكتاب قد تم استلامه أو إرجاعه، رمي استثناء
            throw new \Exception('You cannot update the borrow record after the book has been taken or returned');
        }
        
    }
    //===========================================================================================================================
    public function updateBorrowStatusForAdmin($data, $id)
    {
        //التعديل على حالة الكتاب ليصبح منتهي الحالة
        //أي أن المستخدم قد أعاد الكتاب
        $borrowRecord = BorrowRecord::findOrFail($id);
    
        if ($data['status'] === 'the book Has been returned') {
            //في حال كان زمن الإرجاع الكتاب أكبر من القيمة المخزنة و هي 14 يوم ,, سيتم تصفير التاريخين معاً
            if ($borrowRecord->returned_at < Carbon::today()->toDateTimeString()) {
                $borrowRecord->update([
                    'due_at' => null,
                    'returned_at' => null,
                    'status' => $data['status'],
                ]);
            } else {
                //في حال كان زمن الإرجاع الكتاب أصغر أو تساوي القيمة المخزنة و هي 14 يوم ,, سيتم تصفير تاريخ الإرجاع فقط 
                $borrowRecord->update([
                    'returned_at' => null,
                    'status' => $data['status'],
                ]);
            }
        }
        
        return $borrowRecord; // تأكد من إعادة السجل بعد التحديث
    }

}

