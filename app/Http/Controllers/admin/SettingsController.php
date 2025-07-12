<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use DB;
use Validator;
use Image;
use File;

class SettingsController extends Controller
{
    public function index(Request $request)
    {

        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'registered_office_address' => 'required',
                    'office_address' => 'required',
                    'phone_number' => 'required',
                    'email_id' => 'required',
                    'whatsapp' => 'required',
                    'customer_care' => 'required',
                ], [
                    'registered_office_address.required' => 'Registered office address is required.',
                    'office_address.required' => 'Office address is required.',
                    'phone_number.required' => 'Phone number is required.',
                    'email_id.required' => 'Email id is required.',
                    'whatsapp.required' => 'Whatsapp is required.',
                    'customer_care.required' => 'Customer care is required.',
                    'gst_number.required' => 'Gst number is required.',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }

                DB::beginTransaction();


                $array = [
                    'registered_office_address' => $request->registered_office_address,
                    'registered_office_address2' => $request->registered_office_address2,
                    'office_address2' => $request->office_address2,
                    'office_address' => $request->office_address,
                    'phone_number' => $request->phone_number,
                    'email_id' => $request->email_id,
                    'whatsapp' => $request->whatsapp,
                    'customer_care' => $request->customer_care,
                    'gst_number' => $request->gst_number,
                    'updated_by' => auth()->user()->id,

                ];
                if (request('setting_id')) {
                    $setting_id = request('setting_id');
                } else {
                    $setting_id = null;
                    $array['created_by'] =auth()->user()->id;
                }
                $response = Setting::UpdateOrCreate(['id' => $setting_id], $array);
                DB::commit();
                return redirect('admin/settings')->with('success', 'Settings details updated successfully.');
            }
            $page_title = 'Admin Settings';
            $page_description = '';
            $breadcrumbs = [
                [
                    'title' => 'Settings',
                    'url' => '',
                ]
            ];


            $status = request('status');
            if ($status == '0') {
                $status = '2';
            }
            $details  = Setting::orderBy('id', 'desc')->first();
            return view('admin.pages.settings.settings', compact('page_title', 'page_description', 'breadcrumbs',  'details'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }






















    public function add(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'page_name' => 'required',
                    'seo_title' => 'required',
                    'seo_description' => 'required',
                    'seo_keywords' => 'required',
                ], [
                    'page_name.required' => 'Page name is required.',
                    'seo_title.required' => 'Seo title is required.',
                    'seo_description.required' => 'Seo description no is required.',
                    'seo_keywords.required' => 'Seo keywords is required.',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }

                DB::beginTransaction();


                $array = [
                    'page_name' => $request->page_name,
                    'seo_title' => $request->seo_title,
                    'seo_description' => $request->seo_description,
                    'seo_keywords' => $request->seo_keywords,
                    'status' => 0,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'slug' => \Str::slug($request->page_name)

                ];
                $UserEmail = SeoPage::where('slug', $array['slug'])->exists();
                if ($UserEmail) {
                    return redirect()->back()->withErrors(['Page and page slug already exist.'])->withInput($request->all());
                }
                // dd(  $array );
                $response = SeoPage::UpdateOrCreate(['id' => null], $array);
                DB::commit();
                return redirect('admin/seo/list')->with('success', 'Page details added successfully.');
            }

            $pageSettings = $this->pageSetting('add');

            $page_title =  $pageSettings['page_title'];
            $page_description = $pageSettings['page_description'];
            $breadcrumbs = $pageSettings['breadcrumbs'];

            return view('admin.pages.seo.add', compact('page_title', 'page_description', 'breadcrumbs'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'page_name' => 'required',
                    'seo_title' => 'required',
                    'seo_description' => 'required',
                    'seo_keywords' => 'required',
                ], [
                    'page_name.required' => 'Page name is required.',
                    'seo_title.required' => 'Seo title is required.',
                    'seo_description.required' => 'Seo description no is required.',
                    'seo_keywords.required' => 'Seo keywords is required.',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }

                DB::beginTransaction();


                $array = [
                    'page_name' => $request->page_name,
                    'seo_title' => $request->seo_title,
                    'seo_description' => $request->seo_description,
                    'seo_keywords' => $request->seo_keywords,
                    'status' => 0,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'slug' => \Str::slug($request->page_name)

                ];
                $UserEmail = SeoPage::where('slug', $array['slug'])->where('id', '!=', $id)->exists();
                if ($UserEmail) {
                    return redirect()->back()->withErrors(['Page and page slug already exist.'])->withInput($request->all());
                }
                // dd(  $array );
                $response = SeoPage::UpdateOrCreate(['id' => $id], $array);
                DB::commit();
                return redirect('admin/seo/list')->with('success', 'Page details added successfully.');
            }

            $details = SeoPage::where('id',   $id)->first();
            if ($details) {
                $pageSettings = $this->pageSetting('edit');

                $page_title =  $pageSettings['page_title'];
                $page_description = $pageSettings['page_description'];
                $breadcrumbs = $pageSettings['breadcrumbs'];

                return view('admin.pages.seo.edit', compact('page_title', 'page_description', 'breadcrumbs', 'details'));
            } else {
                return redirect()->back()->withErrors(['Page details not exist.']);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }



    public function delete($id)
    {
        try {
            if ($id) {
                DB::beginTransaction();
                $cat = SeoPage::find($id);
                if ($cat->delete()) {
                    DB::commit();
                    return redirect()->back()->with('success', 'Page deleted successfully.');
                } else {
                    return redirect()->back()->with('error', 'Failed to delete try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Page details not found.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function updateStatus($id, $status)
    {
        try {
            if ($id) {
                DB::beginTransaction();
                $status = ($status == 1) ? $status = 0 : $status = 1;
                $updateArr = [
                    'status' => $status,
                ];
                $response = SeoPage::UpdateOrCreate(['id' => $id], $updateArr);
                DB::commit();
                return redirect('admin/seo/list')->with('success', 'Page status updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Page details not found.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }




    public function pageSetting($action, $dataArray = [])
    {
        if ($action == 'edit') {
            $data['page_title'] = 'Page Master';
            $data['page_description'] = 'Edit Page';
            $data['breadcrumbs'] = [
                [
                    'title' => 'Page Master',
                    'url' => url('admin/seo/list'),
                ]
            ];
            if (isset($dataArray['title']) && !empty($dataArray['title'])) {
                $data['breadcrumbs'][] =
                    [
                        'title' => $dataArray['title'],
                        'url' => '',

                    ];
            }
            return $data;
        }

        if ($action == 'add') {
            $data['page_title'] = 'Page Master';
            $data['page_description'] = 'Add New Page';
            $data['breadcrumbs'] = [
                [
                    'title' => 'Page Master',
                    'url' => url('admin/seo/list'),
                ],
                [
                    'title' => 'Add a New Page',
                    'url' => '',
                ],
            ];
            return $data;
        }
    }
}
